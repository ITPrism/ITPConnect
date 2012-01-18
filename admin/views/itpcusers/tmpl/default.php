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

//JHtml::_('behavior.tooltip');
//JHTML::_('script','system/multiselect.js',false,true);
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
//$saveOrder  = $listOrder == 'a.ordering';
?>
<form action="<?php echo JRoute::_('index.php?option=com_itpconnect&view=itpcusers'); ?>" method="post" name="adminForm" id="adminForm">
    <fieldset id="filter-bar">
        <div class="filter-search fltlft">
            <label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
            <input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_WEBLINKS_SEARCH_IN_TITLE'); ?>" />
            <button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
            <button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
        </div>
    </fieldset>
    <div class="clr"> </div>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" onclick="checkAll(this)" />
                </th>
                <th class="title" >
                    <?php echo JHtml::_('grid.sort', 'Name', 'name', $listDirn, $listOrder); ?>
                </th>
                <th width="100" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort',  'Facebook ID', 'fbuser_id', $listDirn, $listOrder ); ?>
                </th>
                <!-- 
                <th width="100" nowrap="nowrap">
                    <?php echo JHTML::_('grid.sort', 'Twitter ID', 'twuser_id', $listDirn, $listOrder ); ?>
                </th>
                 -->
                <th width="80">
                    <?php echo JHTML::_('grid.sort',  'User ID', 'users_id', $listDirn, $listOrder ); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="4">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
        <?php foreach ($this->items as $i => $item) :
            $item->userLink = JRoute::_('index.php?option=com_users&task=user.edit&id='. $item->users_id);
        ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td class="center">
                    <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                </td>
                <td >
                    <a href="<?php echo $item->userLink; ?>" ><?php echo $item->name; ?></a>
                </td>
                <td>
                    <?php echo $item->fbuser_id;?>
                </td>
                <!--
                <td class="center">
                    <?php echo $item->twuser_id;?>
                </td>
                -->
                <td class="center">
                    <?php echo $item->users_id;?>
                </td>
               
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>

</form>