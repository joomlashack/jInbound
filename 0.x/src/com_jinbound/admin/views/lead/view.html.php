<?php
/**
 * @version		$Id$
 * @package		jInbound
 * @subpackage	com_jinbound
@ant_copyright_header@
 */

defined('_JEXEC') or die;

JLoader::register('JInbound', JPATH_ADMINISTRATOR . "/components/com_jinbound/helpers/jinbound.php");
JInbound::registerLibrary('JInboundItemView', 'views/baseviewitem');

class JInboundViewLead extends JInboundItemView
{
	
}