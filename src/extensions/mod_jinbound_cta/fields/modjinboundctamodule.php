<?php
/**
 * @package             jInbound
 * @subpackage          mod_jinbound_cta
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

jimport('joomla.filesystem.folder');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');

JFormHelper::loadFieldClass('list');

class JFormFieldModJInboundCTAModule extends JFormFieldList
{
    public $type = 'ModJInboundCTAModule';

    protected function getOptions()
    {
        $db = JFactory::getDbo();
        try {
            $options = $db->setQuery($db->getQuery(true)
                ->select('id AS value, title AS text')
                ->from('#__modules')
                ->where('published = 1')
                ->order('title ASC')
            )->loadObjectList();
        } catch (Exception $e) {
            JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
        }
        if (!is_array($options)) {
            $options = array();
        }
        return array_merge(parent::getOptions(), $options);
    }
}
