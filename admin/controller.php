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

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.controller' );

/**
 * ITPConnect Users Controller
 *
 * @package     ITPrism Components
 * @subpackage  ITPConnect
  */
class ItpConnectController extends JController {

    /**
     * Method to display a view.
     *
     * @param   boolean         $cachable   If true, the view output will be cached
     * @param   array           $urlparams  An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController     This object to support chaining.
     * @since   1.5
     */
    public function display($cachable = false, $urlparams = false){

        $document =& JFactory::getDocument();
        /* @var $document JDocumentHTML */
        
        // Add component style
        $document->addStyleSheet(JURI::root() . 'media/com_itpconnect/css/style.css', 'text/css', null);
        
        $vName      = JRequest::getCmd('view', 'cpanel');
        JRequest::setVar("view", $vName);

        parent::display();
        return $this;
        
    }
    
}