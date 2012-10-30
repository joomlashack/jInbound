<?php
/**
 * @version		$Id$
 * @package		JInbound
 * @subpackage	com_jinbound
@ant_copyright_header@
 */

defined('JPATH_PLATFORM') or die;

JLoader::register('JInboundView', JPATH_ADMINISTRATOR.'/components/com_jinbound/libraries/views/baseview.php');

class JInboundListView extends JInboundView
{
	
	protected $items;
	protected $pagination;
	protected $state;

	function display($tpl = null, $echo = true) {
		$items = $this->get('Items');
		$pagination = $this->get('Pagination');
		$state = $this->get('State');
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		$this->items = $items;
		$this->pagination = $pagination;
		$this->state = $state;
		
		$this->addFilter(JText::_('COM_JINBOUND_SELECT_PUBLISHED'), 'filter_published', $this->get('PublishedStatus'), $state->get('filter.published'));
		
		$this->addToolBar();
		$this->addMenuBar();
		
		JCalPro::debugger('Items', $this->items);
		JCalPro::debugger('Pagination', $this->pagination);
		
		parent::display($tpl);
	}
	
	public function addFilter($label, $name, $options, $default) {
		$filter = new stdClass;
		$filter->label   = $label;
		$filter->name    = $name;
		$filter->options = $options;
		$filter->default = $default;
		if (!is_array($this->_filters)) $this->_filters = array();
		return $this->_filters[] = $filter;
	}
	
	public function renderFilters() {
		if (empty($this->_filters)) return;
		if (JInbound::version()->isCompatible('3.0')) {
			foreach ($this->_filters as $filter) {
				array_shift($filter->options);
				$options = JHtml::_('select.options', $filter->options, 'value', 'text', $filter->default, true);
				JSubMenuHelper::addFilter($filter->label, $filter->name, $options);
			}
			return;
		}
		foreach ($this->_filters as $filter) {
			$this->currentFilter = JHtml::_('select.genericlist', $filter->options, $filter->name, sprintf('id="%s" class="listbox" onchange="this.form.submit()"', $filter->name), 'value', 'text', $filter->default);
			echo $this->loadTemplate('filter');
		}
	}

	public function addToolBar() {
		// only fire in administrator
		if (!JFactory::getApplication()->isAdmin()) return;
		// yuk - fix this later :P
		// TAKE NOTE: so far all the views have single names that are pluralized by adding an 's'
		// if this ever changes, this needs to be replaced with more comprehensive inflector code
		$single = preg_replace('/s$/', '', $this->_name);
		// set the toolbar title
		JToolBarHelper::title(JText::_(strtoupper(JInbound::COM.'_'.$this->_name.'_MANAGER')), 'jinbound-'.strtolower($this->_name));
		if (JFactory::getUser()->authorise('core.create')) {
			JToolBarHelper::addNew($single . '.add', 'JTOOLBAR_NEW');
		}
		if (JFactory::getUser()->authorise('core.edit') || JFactory::getUser()->authorise('core.edit.own')) {
			JToolBarHelper::editList($single . '.edit', 'JTOOLBAR_EDIT');
			JToolBarHelper::divider();
		}
		if (JFactory::getUser()->authorise('core.edit.state')) {
			JToolBarHelper::publish($this->_name . '.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish($this->_name . '.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::checkin($this->_name . '.checkin');
			JToolBarHelper::divider();
		}
		if ($this->state->get('filter.published') == -2 && JFactory::getUser()->authorise('core.delete', JInbound::COM)) {
			JToolBarHelper::deleteList('', $this->_name . '.delete', 'JTOOLBAR_EMPTY_TRASH');
		}
		else if (JFactory::getUser()->authorise('core.edit.state')) {
			JToolBarHelper::trash($this->_name . '.trash');
			JToolBarHelper::divider();
		}
		// add parent toolbar
		parent::addToolBar();
	}
}