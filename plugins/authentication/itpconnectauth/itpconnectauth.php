<?php
/**
 * @package      ITPrism Plugins
 * @subpackage   ITPConnect
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPConnect is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');
jimport('joomla.plugin.plugin');

/**
 * ITPConnect Authentication Plugin
 *
 * @package      ITPrism Plugins
 * @subpackage   ITP Connect
 */
class plgAuthenticationItpConnectAuth extends JPlugin {
    
    /**
     * This method should handle any authentication and report back to the subject
     *
     * @access	public
     * @param	array	$credentials	Array holding the user credentials
     * @param	array	$options		Array of extra options
     * @param	object	$response		Authentication response object
     * @return	boolean
     * @since	1.5
     */
    public function onUserAuthenticate($credentials, $options, &$response){
        
        $success = false;
        
        $itpConnectParams = JComponentHelper::getParams('com_itpconnect');
        if(!$itpConnectParams->get("facebookOn")){
            return $success;
        }
        
        if(!JComponentHelper::isEnabled('com_itpconnect', true)){
            return $success;
        }
        
        $app = & JFactory::getApplication();
        /* @var $app JApplication */
        
        if($app->isAdmin()){
            return $success;
        }
        
        $response->type = 'ItpConnect';
        $fbUserId       = ItpcHelper::getFB()->getUser();
        
        if(!$fbUserId){
            $response->status        = JAUTHENTICATE_STATUS_FAILURE;
            $response->error_message = 'Could not authenticate';
        }else{
            
            $userId 				 = ItpcHelper::getJUserId($fbUserId);
            $user 					 = JUser::getInstance($userId); // Bring this in line with the rest of the system
            if(!$user) {
                $response->status        = JAUTHENTICATE_STATUS_FAILURE;
                $response->error_message = 'Could not authenticate';
            } else {
                $response->email         = $user->email;
                $response->fullname      = $user->name;
                if (JFactory::getApplication()->isAdmin()) {
					$response->language = $user->getParam('admin_language');
				}
				else {
					$response->language = $user->getParam('language');
				}
				
                $response->status        = JAUTHENTICATE_STATUS_SUCCESS;
                $response->error_message = '';
                $success = true;
            }
            
        }
        
        return $success;
    }
}
