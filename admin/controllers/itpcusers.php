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

// No direct access.
defined('_JEXEC') or die();

jimport('joomla.application.component.controlleradmin');

/**
 * ITPConnect Users list controller class.
 *
 * @package     ITPrism Components
 * @subpackage  ITPConnect
 * @since       1.6
 */
class ItpConnectControllerItpcUsers extends JControllerAdmin {
    
    /**
     * Removes items
     *
     */
    public function delete(){
        
        $msg = "";
        
        // Check for request forgeries.
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        
        $cids = JRequest::getVar('cid', array(), 'post', 'array');
        JArrayHelper::toInteger($cids);
        
        try {
            // Gets the model
            $model = $this->getModel('ItpcUsers', "ItpConnectModel");
            $model->delete($cids);
            
            $msg = JText::_('COM_ITPCONNECT_USERS_DELETED');
            
        } catch (ItpUserException $e) {
            $msg = "";
            JError::raiseWarning(500, $e->getMessage());
        } catch (Exception $e) {
            
            $itpSecurity = new ItpSecurity($e);
            $itpSecurity->AlertMe();
           
            JError::raiseError(500, JText::_('ITP_ERROR_SYSTEM'));
            jexit();
            
        }
        
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false), $msg);
    
    }
    
    public function cancel() {
       $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view=cpanel', false));
    }

}