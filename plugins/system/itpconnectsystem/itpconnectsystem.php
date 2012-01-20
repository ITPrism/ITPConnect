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
* ItpConnect plugin
*
* @package 		ITPrism Plugins
* @subpackage	ITPConnect
* @todo         Optimisation of the number of queries to the DB ( Checking of enabled component, isAdmin,... )
*/
class plgSystemItpConnectSystem extends JPlugin {
	
    public $itpConnectParams; 
    
    public function __construct(&$subject, $config = array()){
        
        parent::__construct($subject, $config);
        
        $this->itpConnectParams = JComponentHelper::getParams('com_itpconnect');
    }
    
    /**
     * Connect and initialise facebook object
     */
    public function onAfterInitialise() {
        
        if(!$this->itpConnectParams->get("facebookOn", 0)){
            return;
        }
        
        if (!JComponentHelper::isEnabled('com_itpconnect', true)) {
            return;
        }

        $libsPath   = JPATH_ROOT . DS. "administrator" . DS . "components" . DS ."com_itpconnect" . DS."libraries" . DS;
        require_once($libsPath."itpinit.php");
        
        JHTML::_('behavior.framework', true);
    }
    
	/**
     * Put a code that connect to site with authentication services - Facebook, Twitter,...
     */
	public function onAfterRender() {
		
	    if(!$this->itpConnectParams->get("facebookOn", 0)){
            return;
        }
        
		if (!JComponentHelper::isEnabled('com_itpconnect', true)) {
			return;
        }

	    $app = JFactory::getApplication();
        /* @var $app JApplication */

        if($app->isAdmin()) {
            return;
        }
	    
	    $doc   = JFactory::getDocument();
        /* @var $doc JDocumentHTML */
        $docType = $doc->getType();
        
        // Joomla! must render content of this plugin only in HTML document
        if(strcmp("html", $docType) != 0){
            return;
        }
        
        // Get language
    	if($this->itpConnectParams->get("fbDynamicLocale", 0)) {
            $lang   = JFactory::getLanguage();
            $locale = $lang->getTag();
            $locale = str_replace("-","_",$locale);
        } else {
            $locale = $this->itpConnectParams->get("fbLocale", "en_US");
        }

        // Set AJAX loader
        $ajaxLoader = "";
        if($this->itpConnectParams->get("fbDisplayAjaxLoader")) {
            $ajaxLoader = "document.id('itpconnect-ajax-loader').show();";
        }
        
        // Get return page
	    $return = ItpcHelper::getReturn();
        $return = base64_encode($return);
        
        $buffer = JResponse::getBody();
		
//		$pattern = "/<body[^>]*>/s";
        $pattern = "/<\/body[^>]*>/s";
		$matches = array();
		if(preg_match($pattern,$buffer,$matches)){
			
	        // Add Facebook tags description into html tag
//	        $newHtmlAttr = '<html xmlns:fb="http://www.facebook.com/2008/fbml" '; 
//	        $buffer = str_replace("<html",$newHtmlAttr,$buffer);
	        
//	        $newBodyTag = $matches[0] . "
			$newBodyTag = "
    <div id='fb-root'></div>

    <script>
	function itpLogin() {
    	window.location = 'index.php?option=com_itpconnect&task=facebook.connect&return=".$return."'
    }
    
    window.fbAsyncInit = function() {
        FB.init({
          appId      : '" . $this->itpConnectParams->get("facebookAppId") . "', // App ID
          status     : true, // check login status
          cookie     : true, // enable cookies to allow the server to access the session
          xfbml      : true,  // parse XFBML
          oauth  	: true
        });
    
        // Additional initialization code here
        // whenever the user logs in, we refresh the page
        FB.Event.subscribe('auth.login', function() {
          $ajaxLoader
        });
        
        // whenever the user logs in, we refresh the page
        FB.Event.subscribe('auth.logout', function() {
          	window.location.reload();
        });
        
      };
      
    </script>
    
    <script>(function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = \"//connect.facebook.net/" . $locale . "/all.js\";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>

    " ;
			
            $newBodyTag .= $matches[0];
            
			$buffer = str_replace($matches[0],$newBodyTag,$buffer);
		}
		
		JResponse::setBody($buffer);
		return;
	}
	
}
