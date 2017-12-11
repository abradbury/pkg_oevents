<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
class OEventsViewEvent extends JViewLegacy {
	
	protected $form = null;
 
	/**
	 * Display the OEvent view
	 *
	 * @param   string  $tpl  The name of the template file to parse; 
	 *		automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null) {
		// Get the Data
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
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
		$input = JFactory::getApplication()->input;
 
		// Hide Joomla Administrator Main menu
		$input->set('hidemainmenu', true);
 
		$isNew = ($this->item->event_id == 0);
 
		if ($isNew) {
			$title = JText::_('COM_OEVENTS') . ' - ' . JText::_('COM_OEVENTS_MANAGER_OEVENT_NEW');
		}
		else {
			$title = JText::_('COM_OEVENTS') . ' - ' . JText::_('COM_OEVENTS_EDIT_EVENT');
		}
 
		JToolBarHelper::title($title, 'event');
		JToolBarHelper::save('event.save');
		JToolBarHelper::cancel(
			'event.cancel',
			$isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
		);
	}
}