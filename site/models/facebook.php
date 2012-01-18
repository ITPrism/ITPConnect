<?php
/**
 * @package      ITPrism Components
 * @subpackage   ITPConnect
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPConnect is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modelitem');

/**
 * It is a facebook model
 * 
 * @author Todor Iliev
 */
class ITPConnectModelFacebook extends JModelItem {
    
    /**
     * Model context string.
     *
     * @access  protected
     * @var     string
     */
    protected $_context = 'com_itpconnect.facebook';
    
    public function __construct(){
        parent::__construct();
    }
    
    /**
     * Method to get data
     *
     * @since 1.5
     */
    public function getItem($fbUserId){
        
        settype($fbUserId,"string");
        $fbUserId = JString::trim($fbUserId);
        
        $db = JFactory::getDbo();
        
        $query = "
          SELECT  
              *
          FROM 
              `#__itpc_users`
          WHERE
              `fbuser_id` = " . $db->Quote($fbUserId);
        
        $db->setQuery($query);
        
        $this->_item = $db->loadObject();
        
        if($db->getErrorNum() != 0){
            throw new ItpException($db->getErrorMsg(), 500);
        }
        
        return $this->_item;
    }
    
    /**
     * Create a new user
     * 
     * @param $fbUserId  A Facebook User ID
     * 
     * @return     User id
     */
    public function store($fbUserId, $fbUserData){
        
        settype($fbUserId,"string");
        $fbUserId = JString::trim($fbUserId);
        if(!$fbUserId){
            throw new ItpException( JText::_( 'ITP_ERROR_FB_ID'), 404 );
        }
        
        // Check for existing e-mail (user)
        $userId = ItpcHelper::getJUserIdByEmail($fbUserData['email']);
        
        // Initialise the table with JUser.
        $user = JUser::getInstance();
        
        if(!$userId){
            
            $config = JFactory::getConfig();
            
            // Initialise the table with JUser.
            $user = new JUser();
            $data = (array)$this->getData();
            
            jimport('joomla.user.helper');
            
            // Prepare the data for the user object.
            $data['name'] = $fbUserData['name'];
            $data['email'] = $fbUserData['email'];
            $data['username'] = substr($fbUserData['email'], 0, strpos($fbUserData['email'], "@"));
            $data['password'] = $password = JUserHelper::genRandomPassword();
            $data['block'] = 0;
            
            // Bind the data.
            if(!$user->bind($data)){
                throw new ItpException($user->getError(), 500);
            }
            
            // Load the users plugin group.
            JPluginHelper::importPlugin('user');
            
            // Store the data.
            if(!$user->save()){
                throw new ItpException($user->getError(), 500);
            }
        
            // Send a confirmation mail
            $this->sendConfirmationMail($data, $password);
            
        }else{
            $user->load($userId);
        }
        
        // Loads a record from database
        $row = $this->getTable("itpcuser", "ItpConnectTable");
        $row->load($fbUserId, "facebook");
        
        // Initialize object for new record
        if(!$row->id){
            $row = $this->getTable("itpcuser", "ITPConnectTable");
        }
        
        $row->set("users_id", $user->id);
        $row->set("fbuser_id", $fbUserId);
        
        if (!$row->store()) {
           throw new ItpException($row->getError() , 500);
        }
        
        return $row->users_id;
    
    }
    
    /**
     * Send confirmation e-mail
     * 
     * @param array  User data
     * @param string User password in raw format
     * 
     * @return bool True on success
     */
    private function sendConfirmationMail($data, $password) {
        
        $result = true;
        $app    = JFactory::getApplication();
        /* @var $app JApplication */
        
        $params = $app->getParams("com_itpconnect");
        
        $config = JFactory::getConfig();
                
        if($params->get("fbSendConfirmationMail")) {
            $fromname   = $config->get('fromname');
            $mailfrom   = $config->get('mailfrom');
            $sitename   = $config->get('sitename');
            $siteurl    = JUri::base();
            
            $emailSubject   = JText::sprintf(
                'COM_ITPCONNECT_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_SUBJECT',
                $data['name'],
                $sitename
            );

            $emailBody = JText::sprintf(
                'COM_ITPCONNECT_EMAIL_ACTIVATED_BY_ADMIN_ACTIVATION_BODY',
                $data['name'],
                $siteurl,
                $data['username'],
                $password
            );

            $result = JUtility::sendMail($mailfrom, $fromname, $data['email'], $emailSubject, $emailBody);
            
        }
        
        return $result;
    }
    
    private function getData(){
        
        $params = JComponentHelper::getParams('com_users');
        $data = new stdClass();
        
        // Get the groups the user should be added to after registration.
        $data->groups = isset($data->groups) ? array_unique($data->groups) : array();
        // Get the default new user group, Registered if not specified.
        $system = $params->get('new_usertype', 2);
        $data->groups[] = $system;
        
        // Unset the passwords.
        unset($data->password1);
        unset($data->password2);
        
        // Get the dispatcher and load the users plugins.
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('user');
        
        // Trigger the data preparation event.
        $results = $dispatcher->trigger('onContentPrepareData', array('com_users.registration', $data));
        
        // Check for errors encountered while preparing the data.
        if(count($results) && in_array(false, $results, true)){
            throw new ItpException($dispatcher->getError(), 500);
        }
        
        return $data;
    }

}