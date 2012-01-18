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

jimport('joomla.application.component.view');

class ItpConnectViewItpcUsers extends JView {
    
    protected $items;
    protected $pagination;
    protected $state;
    
    public function display($tpl = null){
        
        $this->assignRef("items",$this->get("Items"));
        $this->assignRef("state",$this->get('State'));
        $this->assignRef("pagination",$this->get('Pagination'));
        
        // Check for errors.
        $errors = $this->get('Errors');
        if (count($errors)) {
            JError::raiseError(500,implode("\n", $errors));
        }

        $this->addToolbar();
        
        parent::display($tpl);
    }
    
    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar(){
            JToolBarHelper::title(JText::_("COM_ITPCONNECT_USERS_MANAGEMENT"), 'itp-users');
            JToolBarHelper::deleteList('', 'itpcusers.delete','JTOOLBAR_DELETE');
            JToolBarHelper::cancel('itpcusers.cancel', 'JTOOLBAR_CANCEL');
    }

}