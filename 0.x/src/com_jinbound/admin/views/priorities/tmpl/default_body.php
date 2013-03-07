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
$saveOrder = ($listOrder == 'Priority.id');
$trashed   = (-2 == $this->state->get('filter.published'));

if (JInbound::version()->isCompatible('3.0')) JHtml::_('dropdown.init');



if (!empty($this->items)) :
	foreach($this->items as $i => $item):

		$canEdit    = $user->authorise('core.edit', JInbound::COM.'.priority.'.$item->id);
		$canEditOwn = $user->authorise('core.edit.own', JInbound::COM.'.priority.'.$item->id) && $item->created_by == $userId;
		$canChange  = $user->authorise('core.edit.state', JInbound::COM.'.priority.'.$item->id);
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td class="hidden-phone">
			<?php echo $item->id;  ?>
		</td>
		<td class="hidden-phone">
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td class="nowrap has-context">
			<div class="pull-left">
				<?php if ($item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->author_name, $item->checked_out_time, 'priorities.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit || $canEditOwn) : ?>
					<a href="<?php echo JInboundHelperUrl::edit('priority', $item->id); ?>">
						<?php echo $this->escape($item->name); ?>
					</a>
				<?php else : ?>
					<?php echo $this->escape($item->name); ?>
				<?php endif; ?>
				<?php  echo $item->name   ?>
			</div>
			<?php if (JInbound::version()->isCompatible('3.0')) : ?>
			<div class="pull-left"><?php

				JHtml::_('dropdown.edit', $item->id, 'priority.');
				JHtml::_('dropdown.divider');
				JHtml::_('dropdown.' . ($item->published ? 'un' : '') . 'publish', 'cb' . $i, 'priorities.');
				if ($item->checked_out) :
					JHtml::_('dropdown.checkin', 'cb' . $i, 'priorities.');
				endif;
				JHtml::_('dropdown.' . ($trashed ? 'un' : '') . 'trash', 'cb' . $i, 'priorities.');

				echo JHtml::_('dropdown.render');

			?></div>
			<?php endif; ?>
		</td>
		<td class="hidden-phone">
				&nbsp;<?php echo JHtml::_('jgrid.published', $item->published, $i, 'priorities.', $canChange, 'cb'); ?>
		</td>
		<td class="hidden-phone">
				&nbsp;<?php  echo $item->description;   ?>
		</td>
	</tr>
	<?php endforeach;
endif;