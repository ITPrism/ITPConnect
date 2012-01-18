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

jimport( 'joomla.application.component.controller' );

class ITPConnectControllerFacebook extends JController {
    
    public function connect(){
        
        $facebook = ItpcHelper::getFB();
        $session  = $facebook->getSession();
        
        $me = null;
        // Session based API call.
        if($session){
            try{
                $uid = $facebook->getUser();
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
                $data  = $model->getItem($uid);
                
                if(!$data){
                    // Create new user or Connect existing user with the facebook.json profile 
                    $userId = $model->store($uid, $me);
                    $data   = $model->getItem($uid);
                }
                
                $user = JUser::getInstance($data->users_id);
                
                $credentials['username'] = $user->get("username");
                $credentials['password'] = $user->get("password");
                
                $options = array();
                $options['remember']     = JRequest::getBool('remember', true);
                $options['return']       = "";
                
                $app = & JFactory::getApplication();
                /* @var $app JApplication */
                
                //preform the login action
                $error = $app->login($credentials, $options);
                
                if(!JError::isError($error)){
                    ItpResponse::sendJsonMsg("All is OK", 1);
                }else{
                    throw new ItpException($error->getMessages(), 500);
                }
                
            } catch ( Exception $e ) {
                
                $itpSecurity = new ItpSecurity($e);
                $itpSecurity->AlertMe();
               
                ItpResponse::sendJsonMsg("Error on login",0);   
                
            }
        
        }
    
    }

} 