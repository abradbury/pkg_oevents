<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');
 
/**
 * OEvents View
 *
 * @since  0.0.1
 */
class OEventsViewEvents extends JViewLegacy {
	
	/**
	 * Display the OEvents view
	 *
	 * @param   string  $tpl  The name of the template file to parse; 
	 * 		automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null) {
		// Get data from the model
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->params 		= JComponentHelper::getParams('com_oevents');
 
		// Check for errors.
		if (is_countable($errors = $this->get('Errors')) ? count($errors = $this->get('Errors')) : 0) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolBar() {
		JToolBarHelper::title(JText::_('COM_OEVENTS_MANAGER_OEVENTS'));
		JToolBarHelper::addNew('event.add');
		JToolBarHelper::editList('event.edit');
		JToolBarHelper::deleteList('', 'events.delete');
		JToolbarHelper::custom('events.refresh', 'refresh', 'refresh', 'COM_OEVENTS_REFRESH', false);

		JToolBarHelper::preferences('com_oevents');
	}
}