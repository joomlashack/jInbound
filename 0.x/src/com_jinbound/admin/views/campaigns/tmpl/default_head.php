<?php
/**
 * @version		$Id$
 * @package		JInbound
 * @subpackage	com_jinbound
@ant_copyright_header@
 */

defined('JPATH_PLATFORM') or die;

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$saveOrder = ($listOrder == 'Campaign.id');
?>
<tr>
	<th width="1%" class="nowrap hidden-phone">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>
	<th width="1%" class="nowrap hidden-phone">
		<?php echo JText::_('COM_JINBOUND_ID'); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_JINBOUND_CAMPAIGN_NAME', 'Campaign.Name', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_JINBOUND_EMAIL_NAME', 'Campaign.EmailName', $listDirn, $listOrder); ?>
	</th>
			<th>
		<?php echo JHtml::_('grid.sort', 'COM_JINBOUND_CAMPAIGN_ACTIVE', 'Campaign.Active', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_JINBOUND_CAMPAIGN_SCHEDULE', 'Campaign.Schedule', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_JINBOUND_CAMPAIGN_USERS', 'Campaign.Users', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_JINBOUND_CAMPAIGN_OPEN', 'Campaign.Open', $listDirn, $listOrder); ?>
	</th>
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_JINBOUND_CAMPAIGN_CLICK', 'Campaign.Click', $listDirn, $listOrder); ?>
	</th>
</tr>