<?php
/**
 * @version		$Id$
 * @package		JInbound
 * @subpackage	com_jinbound
@ant_copyright_header@
 */

defined('JPATH_PLATFORM') or die;

JLoader::register('JInboundView', JPATH_ADMINISTRATOR.'/components/com_jinbound/libraries/views/baseview.php');

class JInboundItemView extends JInboundView
{
	function display($tpl = null, $echo = true) {
		$form = $this->get('Form');
		$item = $this->get('Item');
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// quickfix
		if (is_object($item) && !property_exists($item, 'id')) {
			$item->id = 0;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		$this->canDo = JInbound::getActions();
		$this->addToolBar();
		
		parent::display($tpl);
		$this->setDocument();
	}

	public function addToolBar() {
		// only fire in administrator
		$app = JFactory::getApplication();
		if (!$app->isAdmin()) return;
		$app->input->set('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId = $user->id;
		$isNew = (@$this->item->id == 0);
		$checkedOut = false;
		if ($this->item && property_exists($this->item, 'checked_out')) {
			$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		}

		JToolBarHelper::title(JText::_(strtoupper(JInbound::COM) . '_' . strtoupper($this->_name) . '_MANAGER_' . ($checkedOut ? 'VIEW' : ($isNew ? 'ADD' : 'EDIT'))), 'jinbound-'.strtolower($this->_name));

		if ($isNew) {
			if ($user->authorise('core.create')) {
				JToolBarHelper::apply(strtolower($this->_name).'.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save(strtolower($this->_name).'.save', 'JTOOLBAR_SAVE');
				JToolBarHelper::custom(strtolower($this->_name).'.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
			}
			JToolBarHelper::cancel(strtolower($this->_name).'.cancel', 'JTOOLBAR_CANCEL');
		} else {
			// Can't save the record if it's checked out.
			if (!$checkedOut) {
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($user->authorise('core.edit') || ($user->authorise('core.edit.own') && $this->item->created_by == $userId)) {
					JToolBarHelper::apply(strtolower($this->_name).'.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save(strtolower($this->_name).'.save', 'JTOOLBAR_SAVE');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($user->authorise('core.create')) {
						JToolBarHelper::custom(strtolower($this->_name).'.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
			}

			// If checked out, we can still save
			if ($user->authorise('core.create')) {
				JToolBarHelper::custom(strtolower($this->_name).'.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
			}
			JToolBarHelper::cancel(strtolower($this->_name).'.cancel', 'JTOOLBAR_CLOSE');
		}
	}

	public function setDocument() {
		jimport('joomla.filesystem.file');
		$isNew = ($this->item->id < 1);
		$document = JFactory::getDocument();
		$document->setTitle(JText::_(strtoupper(JInbound::COM).'_'.strtoupper($this->_name).'_'.($isNew ? 'CREATING' : 'EDITING')));
	}
}