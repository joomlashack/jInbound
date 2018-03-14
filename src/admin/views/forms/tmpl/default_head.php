<?php
/**
 * @package             jInbound
 * @subpackage          com_jinbound
 **********************************************
 * jInbound
 * Copyright (c) 2013 Anything-Digital.com
 * Copyright (c) 2018 Open Source Training, LLC
 **********************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.n *
 * This header must not be removed. Additional contributions/changes
 * may be added to this header as long as no information is deleted.
 */

defined('JPATH_PLATFORM') or die;

$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$saveOrder = ($listOrder == 'Form.id');
?>
<tr>
    <th width="1%" class="nowrap hidden-phone">
        <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);"/>
    </th>
    <th width="1%" class="nowrap">
        <?php echo JHtml::_($this->sortFunction, 'COM_JINBOUND_PUBLISHED', 'Form.published', $listDirn, $listOrder); ?>
    </th>
    <th class="nowrap">
        <?php echo JHtml::_($this->sortFunction, 'COM_JINBOUND_TITLE', 'Form.title', $listDirn, $listOrder); ?>
    </th>
    <th width="1%" class="nowrap hidden-phone">
        <?php echo JHtml::_($this->sortFunction, 'COM_JINBOUND_FIELD_COUNT', 'FormFieldCount', $listDirn,
            $listOrder); ?>
    </th>
    <th width="1%" class="nowrap hidden-phone hidden-tablet">
        <?php echo JHtml::_($this->sortFunction, 'COM_JINBOUND_ID', 'Form.id', $listDirn, $listOrder); ?>
    </th>
</tr>
