<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
namespace OEvents\Component\OEvents\Administrator\View\Event;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Toolbar\ToolbarHelper;
use \Joomla\CMS\MVC\View\GenericDataException;
use \Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
class HtmlView extends BaseHtmlView {
	
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
            throw new GenericDataException(implode("\n", $errors), 500);
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
		$input = Factory::getApplication()->input;
 
		// Hide Joomla Administrator Main menu
		$input->set('hidemainmenu', true);
 
		$isNew = ($this->item->event_id == 0);
 
		if ($isNew) {
			$title = Text::_('COM_OEVENTS') . ' - ' . Text::_('COM_OEVENTS_MANAGER_OEVENT_NEW');
		}
		else {
			$title = Text::_('COM_OEVENTS') . ' - ' . Text::_('COM_OEVENTS_EDIT_EVENT');
		}
 
		ToolbarHelper::title($title, 'event');
		ToolbarHelper::save('event.save');
		ToolbarHelper::cancel(
			'event.cancel',
			$isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE'
		);
	}
}