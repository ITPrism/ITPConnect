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

// Include dependencies
jimport('joomla.application.component.controller');

require_once (JPATH_COMPONENT_ADMINISTRATOR . DS. "libraries" . DS ."itpinit.php");

// Get an instance of the controller 
$controller = JController::getInstance("ItpConnect");

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();