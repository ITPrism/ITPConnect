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
defined('_JEXEC') or die;

jimport( 'joomla.application.component.controller' );

class ITPConnectControllerFacebook extends JController {
    
    public function connect(){
        
        // Get return page
	    $return    = base64_decode(JRequest::getVar('return', '', 'GET', 'BASE64'));
	    if(empty($return)) {
	        $return = 'index.php?option=com_users&view=profile';
	    }
	    
        $facebook  = ItpcHelper::getFB();
        $fbUserId  = $facebook->getUser();
        
        $me = null;
        // Session based API call.
        if($fbUserId){
            try{
                $me  = $facebook->api('/me');
            }catch(FacebookApiException $e){
                $itpSecurity = new ItpSecurity($e);
                $itpSecurity->AlertMe();
                $me = null;
            }
        }
        
        if($me){
            
            try {
                // Get model
                $model = $this->getModel("Facebook", "ITPConnectModel");
                $data  = $model->getItem($fbUserId);
                
                if(!$data){
                    // Create new user or Connect existing user with the facebook profile 
                    $userId = $model->store($fbUserId, $me);
                    $data   = $model->getItem($fbUserId);
                }
                
                $user = JUser::getInstance($data->users_id);
                
                $credentials['username'] = $user->get("username");
                $credentials['password'] = $user->get("password");
                
                $options = array();
                $options['remember']     = JRequest::getBool('remember', true);
                $options['return']       = $return;
                
                $app = & JFactory::getApplication();
                /* @var $app JApplication */
                
                //preform the login action
                $error = $app->login($credentials, $options);
                
                if(JError::isError($error)){
                    throw new ItpException($error->getMessages(), 500);
                }
                
            } catch ( Exception $e ) {
                
                $itpSecurity = new ItpSecurity($e);
                $itpSecurity->AlertMe();
               
                return $this->setRedirect(JRoute::_("index.php"));   
                
            }
        
        }
    
        $this->setRedirect(JRoute::_($return));
    }

} 