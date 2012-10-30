<?php
/**
 * @version		$Id$
 * @package		JInbound
 * @subpackage	com_jinbound
@ant_copyright_header@
 */

defined('JPATH_PLATFORM') or die;

$user      = JFactory::getUser();
$userId    = $user->get('id');
$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$saveOrder = ($listOrder == 'Page.id');
$trashed   = (-2 == $this->state->get('filter.published'));

if (JInbound::version()->isCompatible('3.0')) JHtml::_('dropdown.init');

if (!empty($this->items)) :
	foreach($this->items as $i => $item):
		$canEdit    = $user->authorise('core.edit', JInbound::COM.'.page.'.$item->id);
		$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
		$canEditOwn = $user->authorise('core.edit.own', JInbound::COM.'.page.'.$item->id) && $item->created_by == $userId;
		$canChange  = $user->authorise('core.edit.state', JInbound::COM.'.page.'.$item->id) && $canCheckin;
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td class="hidden-phone">
			<?php echo $item->id; ?>
		</td>
		<td class="hidden-phone">
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td class="nowrap has-context">
			<div class="pull-left">
				<?php if ($item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->author_name, $item->checked_out_time, 'pages.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit || $canEditOwn) : ?>
					<a href="<?php echo JInboundHelperUrl::edit('page', $item->id); ?>">
						<?php echo $this->escape($item->user_name); ?>
					</a>
				<?php else : ?>
					<?php echo $this->escape($item->user_name); ?>
				<?php endif; ?>
			</div>
			<?php if (JInbound::version()->isCompatible('3.0')) : ?>
			<div class="pull-left"><?php
			
				JHtml::_('dropdown.edit', $item->id, 'page.');
				JHtml::_('dropdown.divider');
				JHtml::_('dropdown.' . ($item->published ? 'un' : '') . 'publish', 'cb' . $i, 'pages.');
				if ($item->checked_out) :
					JHtml::_('dropdown.checkin', 'cb' . $i, 'pages.');
				endif;
				JHtml::_('dropdown.' . ($trashed ? 'un' : '') . 'trash', 'cb' . $i, 'pages.');
				
				echo JHtml::_('dropdown.render');
				
			?></div>
			<?php endif; ?>
		</td>
	</tr>
	<?php endforeach;
endif;