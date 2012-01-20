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
defined('_JEXEC') or die();

/**
 * It is the component helper class
 *
 */
class ItpcHelper {
	
    /**
     * Get a return URL for the current page
     * 
     * @return string   Return page
     */
    public static function getReturn() {
        
        $module = JModuleHelper::getModule("itpconnect");
        $return = "";
        
        if(!empty($module->params)) {
            $params     = new JRegistry($module->params);
            
            $type       = ItpcHelper::getType();
            $return     = ItpcHelper::getReturnURL($params, $type);
            $return     = base64_decode($return);
            $return     = JRoute::_($return, false);
        }
        
        if(!$return OR !JURI::isInternal($return)) {
            $return = "/";
        }
        
        return $return;
    }
    
    /**
     * Get the return URL from the ITPConnect module
     * 
     * @param object $params ITPConnect module
     * @param string $type The type of URL
     * @return string
     */
    public static function getReturnURL($params, $type) {
        
        $app    = JFactory::getApplication();
        $router = $app->getRouter();
        $url = null;
        if ($itemid =  $params->get($type))
        {
            $db     = JFactory::getDbo();
            $query  = $db->getQuery(true);

            $query->select($db->nameQuote('link'));
            $query->from($db->nameQuote('#__menu'));
            $query->where($db->nameQuote('published') . '=1');
            $query->where($db->nameQuote('id') . '=' . $db->quote($itemid));

            $db->setQuery($query);
            if ($link = $db->loadResult()) {
                if ($router->getMode() == JROUTER_MODE_SEF) {
                    $url = 'index.php?Itemid='.$itemid;
                }
                else {
                    $url = $link.'&Itemid='.$itemid;
                }
            }
        }
        if (!$url)
        {
            // stay on the same page
            $uri = JFactory::getURI();
            $vars = $router->parse($uri);
            unset($vars['lang']);
            if ($router->getMode() == JROUTER_MODE_SEF)
            {
                if (isset($vars['Itemid']))
                {
                    $itemid = $vars['Itemid'];
                    $menu = $app->getMenu();
                    $item = $menu->getItem($itemid);
                    unset($vars['Itemid']);
                    if (isset($item) && $vars == $item->query) {
                        $url = 'index.php?Itemid='.$itemid;
                    }
                    else {
                        $url = 'index.php?'.JURI::buildQuery($vars).'&Itemid='.$itemid;
                    }
                }
                else
                {
                    $url = 'index.php?'.JURI::buildQuery($vars);
                }
            }
            else
            {
                $url = 'index.php?'.JURI::buildQuery($vars);
            }
        }

        return base64_encode($url);
    }
    
    public static function getType() {
        $user = JFactory::getUser();
        return (!$user->get('guest')) ? 'logout' : 'login';
    }
    
    /**
     * Create an istanse of Facebook object
     * 
     * @return Facebook
     */
    public static function getFB(){
        
        static $instance;

        if (!is_object($instance)) {
            
            jimport('joomla.application.component.helper');

            // Gets parameters
            $params     =   JComponentHelper::getParams('com_itpconnect');
            
            // Create our Application instance (replace this with your appId and secret).
            $instance = new Facebook(array(
                  'appId'  => $params->get("facebookAppId"),
                  'secret' => $params->get("facebookSecret"),
                  'cookie' => true,
                ));
        }

        return $instance;
            
    }
    
    /**
     * Loads Joomla User Id by e-mail
     * 
     * @param string $email
     * @return User Id 
     */
    public static function getJUserIdByEmail($email){
        
        settype($email,"string");
        
        // Initialize some variables
        $db = & JFactory::getDBO();
        
        $query = "
            SELECT 
               `id` 
            FROM 
               `#__users` 
            WHERE 
               `email` = " . $db->Quote($email);
               
        $db->setQuery($query, 0, 1);
        $result = $db->loadResult();
        
        if($db->getErrorNum() != 0){
            throw new ItpException($db->getErrorMsg(), 500);
        }
        
        return $result;
        
    }
    
    /**
     * Loads Joomla User Id by Facebook id
     * 
     * @param integer Facebook Id
     * @return User Id 
     */
    public static function getJUserId($fbId){
        
        settype($fbId,"string");
        
        // Initialize some variables
        $db = & JFactory::getDBO();
 
        $query = "
            SELECT 
               `users_id` 
            FROM 
               `#__itpc_users` 
            WHERE 
               `fbuser_id` = " . $db->Quote($fbId);
               
        $db->setQuery($query, 0, 1);
        $result = $db->loadResult();
        
        if($db->getErrorNum() != 0){
            throw new ItpException($db->getErrorMsg(), 500);
        }
        
        return $result;
    }
    
    
}