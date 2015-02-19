<?php
/**
 * @package		JInbound
 * @subpackage	com_jinbound
@ant_copyright_header@
 */

defined('JPATH_PLATFORM') or die;

JLoader::register('JInbound', JPATH_ADMINISTRATOR.'/components/com_jinbound/helpers/jinbound.php');
JInbound::registerLibrary('JInboundAdminModel', 'models/basemodeladmin');

/**
 * This models supports retrieving lists of forms.
 *
 * @package		JInbound
 * @subpackage	com_jinbound
 */
class JInboundModelForm extends JInboundAdminModel
{
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm($this->option.'.'.$this->name, $this->name, array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	/**
	 * Method to override the parent getItem method to add the field xref data
	 */
	public function getItem($id = null) {
		// load the item
		$item = parent::getItem($id);
		// we may not have a form
		if ($item) {
			// initialize the database object
			$db = JFactory::getDbo();
			// load the data from the xref table from the database 
			$db->setQuery('SELECT CAST(GROUP_CONCAT(field_id ORDER BY ordering ASC SEPARATOR "|") AS CHAR) AS fields FROM #__jinbound_form_fields WHERE form_id = ' . intval($item->id) . ' GROUP BY form_id');
			$fields = $db->loadResult();
			if (empty($fields)) $fields = '';
			// append fields to the item
			$item->formfields = $fields;
		}
		// return the item
		return $item;
	}
	
	public function setDefault($id = 0) {
		// Initialise variables.
		$user = JFactory::getUser();
		$db   = $this->getDbo();
		
		// Access checks.
		if (!$user->authorise('core.edit.state', 'com_jinbound')) {
			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
		}
		
		$table = JTable::getInstance('Form','JInboundTable');
		if (!$table->load((int)$id)) {
			throw new Exception(JText::_('COM_JINBOUND_ERROR_FORM_NOT_FOUND'));
		}
		
		// Reset the default field
		$db->setQuery(
			$db->getQuery(true)
				->update($db->quoteName('#__jinbound_forms'))
				->set($db->quoteName('default') . ' = 0')
				->where($db->quoteName('type') . ' = ' . $db->quote($table->type))
				->where($db->quoteName('default') . ' = 1')
		);
		
		try {
			if (!$db->query()) {
				throw new Exception($db->getErrorMsg());
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		// Set the new default form
		$db->setQuery(
			$db->getQuery(true)
				->update($db->quoteName('#__jinbound_forms'))
				->set($db->quoteName('default') . ' = 1')
				->where($db->quoteName('id') . ' = ' . (int) $id)
		);
		
		try {
			if (!$db->query()) {
				throw new Exception($db->getErrorMsg());
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		// Clean the cache.
		$this->cleanCache();
		
		return true;
	}
	
	public function unsetDefault($id = 0) {
		// Initialise variables.
		$user = JFactory::getUser();
		$db   = $this->getDbo();
		
		// Access checks.
		if (!$user->authorise('core.edit.state', 'com_jinbound')) {
			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
		}
		
		$table = JTable::getInstance('Form','JInboundTable');
		if (!$table->load((int)$id)) {
			throw new Exception(JText::_('COM_JINBOUND_ERROR_FORM_NOT_FOUND'));
		}
		
		// Set the new default form
		$db->setQuery(
			$db->getQuery(true)
				->update($db->quoteName('#__jinbound_forms'))
				->set($db->quoteName('default') . ' = 0')
				->where($db->quoteName('id') . ' = ' . (int) $id)
		);
		
		try {
			if (!$db->query()) {
				throw new Exception($db->getErrorMsg());
			}
		}
		catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
		
		// Clean the cache.
		$this->cleanCache();
		
		return true;
	}
}
