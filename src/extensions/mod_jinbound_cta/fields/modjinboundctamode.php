<?php
/**
 * @package             JInbound
 * @subpackage          mod_jinbound_cta
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

jimport('joomla.filesystem.folder');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');

JFormHelper::loadFieldClass('list');

// load required classes
JLoader::register('JInbound', JPATH_ADMINISTRATOR . '/components/com_jinbound/libraries/jinbound.php');
JInbound::registerHelper('url');

class JFormFieldModJInboundCTAMode extends JFormFieldList
{
    public $type = 'ModJInboundCTAMode';

    protected function getInput()
    {
        $this->insertScript();
        return parent::getInput();
    }

    protected function insertScript()
    {
        global $mod_jinbound_cta_script_loaded;
        if (is_null($mod_jinbound_cta_script_loaded)) {
            $document = JFactory::getDocument();
            if (!JInbound::version()->isCompatible('3.0.0')) {
                // force jQuery
                $document->addScript(JInboundHelperUrl::media() . '/js/jquery-1.9.1.min.js');
            }
            $document->addScript(JUri::root() . 'media/mod_jinbound_cta/js/admin.js');
            $mod_jinbound_cta_script_loaded = true;
        }
    }
}