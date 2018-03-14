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

?>
    <div class="row-fluid">
        <!-- start the container -->
        <div class="well span8 offset2">
            <!-- Report Heading -->
            <div class="row-fluid">
                <div class="span12">
                    <h3 class="text-center"><?php echo JText::_('COM_JINBOUND_AT_A_GLANCE'); ?></h3>
                </div>
            </div>
            <?php echo $this->loadTemplate(null, 'glance'); ?>
        </div>
    </div>
    <div class="row-fluid">
        <!-- start the container -->
        <div class="well span8 offset2">
            <div class="row-fluid">
                <div class="span12">
                    <div id="jinbound-reports-graph" style="width:100%;height:300px"></div>
                </div>
            </div>
        </div>
    </div>
<?php
echo $this->loadTemplate('leads', 'recent');
echo $this->loadTemplate('pages', 'top');
