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

?>
<div id="itp-cpanel">
    <div class="itp-cpitem">
        <a rel="{handler: 'iframe', size: {x: 875, y: 550}, onClose: function() {}}" href="index.php?option=com_config&amp;view=component&amp;component=com_itpconnect&amp;path=&amp;tmpl=component" class="modal">
            <img src="<?php echo JURI::root();?>media/com_itpconnect/images/settings_48.png" alt="<?php echo JText::_("COM_ITPCONNECT_SETTINGS");?>" />
            <span><?php echo JText::_("COM_ITPCONNECT_SETTINGS")?></span> 
        </a>
    </div>
    <div class="itp-cpitem">
        <a href="index.php?option=com_itpconnect&amp;view=itpcusers" >
        <img src="<?php echo JURI::root();?>media/com_itpconnect/images/users_48.png" alt="<?php echo JText::_("COM_ITPCONNECT_USERS");?>" />
            <span><?php echo JText::_("COM_ITPCONNECT_USERS")?></span> 
        </a>
    </div>
</div>
<div id="itp-itprism">
<a href="http://itprism.com/free-joomla-extensions/social-connection-authentication" title="<?php echo JText::_("COM_ITPCONNECT");?>" target="_blank" >
<img src="<?php echo JURI::root() . "media/com_itpconnect/images/itpconnect_logo.png"; ?>" alt="<?php echo JText::_("COM_ITPCONNECT");?>" />
</a>
<a href="http://itprism.com" title="A Product of ITPrism.com"><img src="<?php echo JURI::root() ."media/com_itpconnect/images/product_of_itprism.png"; ?>" alt="A Product of ITPrism.com" /></a>
<p id="itp-vote-link" ><?php echo JText::_("COM_ITPCONNECT_YOUR_VOTE"); ?></p>
<p id="itp-vote-link" ><?php echo JText::_("COM_ITPCONNECT_SPONSORSHIP"); ?></p>
</div>