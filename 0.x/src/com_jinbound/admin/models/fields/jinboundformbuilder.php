<?php
/**
 * @version		$Id$
 * @package		JInbound
 * @subpackage	com_jinbound
 @ant_copyright_header@
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.formfield');

JLoader::register('JInboundFieldView', JPATH_ADMINISTRATOR.'/components/com_jinbound/libraries/views/fieldview.php');

class JFormFieldJinboundFormBuilder extends JFormField
{
	protected $type = 'JinboundFormBuilder';
	
	/**
	 * Builds the input element for the form builder
	 * 
	 * (non-PHPdoc)
	 * @see JFormField::getInput()
	 */
	protected function getInput() {
		$view = $this->getView();
		// set data
		$view->input = $this;
		// return template html
		return $view->loadTemplate();
	}
	
	/**
	 * This method is used in the form display to show extra data
	 * 
	 */
	public function getSidebar() {
		$view = $this->getView();
		// set data
		$view->input = $this;
		// return template html
		return $view->loadTemplate('sidebar');
	}
	
	/**
	 * gets a new instance of the base field view
	 * 
	 * @return JInboundFieldView
	 */
	protected function getView() {
		$viewConfig = array('template_path' => dirname(__FILE__) . '/formbuilder');
		$view = new JInboundFieldView($viewConfig);
		return $view;
	}
	
	/**
	 * public method to fetch the value
	 * 
	 * TODO finish this
	 */
	public function getFormValue() {
		if (empty($this->value)) {
			return array();
		}
		return $this->value;
	}
	
	/**
	 * get the available form fields
	 * 
	 * TODO: make this better later
	 */
	public function getFormFields() {
		return array(
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_FIRST_NAME'),
				'id'   => 'first_name',
				'type' => 'text'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_LAST_NAME'),
				'id'   => 'last_name',
				'type' => 'text'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_EMAIL'),
				'id'   => 'email',
				'type' => 'text'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_WEBSITE'),
				'id'   => 'website',
				'type' => 'text'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_COMPANY_NAME'),
				'id'   => 'company_name',
				'type' => 'text'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_PHONE_NUMBER'),
				'id'   => 'phone_number',
				'type' => 'text'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_ADDRESS'),
				'id'   => 'address',
				'type' => 'textarea'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_TEXT'),
				'id'   => 'text',
				'type' => 'text'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_TEXTAREA'),
				'id'   => 'textarea',
				'type' => 'textarea'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_CHECKBOXES'),
				'id'   => 'checkboxes',
				'type' => 'text'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_RADIO'),
				'id'   => 'radio',
				'type' => 'text'
			))),
			json_decode(json_encode(array(
				'name' => JText::_('COM_JINBOUND_PAGE_FIELD_SELECT'),
				'id'   => 'select',
				'type' => 'text'
			)))
		);
	}
}