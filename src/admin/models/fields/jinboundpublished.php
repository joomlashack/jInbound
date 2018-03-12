<?php
/**
 * @package             JInbound
 * @subpackage          com_jinbound
 **********************************************
 * JInbound
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

jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldJinboundPublished extends JFormFieldList
{
    public $type = 'Jinboundpublished';

    protected function getOptions()
    {
        // list of published types
        $list   = array();
        $list[] = JHtml::_('select.option', 0, JText::_('COM_JINBOUND_UNPUBLISHED'));
        $list[] = JHtml::_('select.option', 1, JText::_('COM_JINBOUND_PUBLISHED'));
        return array_merge(parent::getOptions(), $list);
    }
}