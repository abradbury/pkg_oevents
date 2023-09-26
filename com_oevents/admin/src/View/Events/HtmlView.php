<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
namespace OEvents\Component\OEvents\Administrator\View\Events;

use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Toolbar\ToolbarHelper;
use \Joomla\CMS\Component\ComponentHelper;
use \Joomla\CMS\MVC\View\GenericDataException;
use \Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * OEvents View
 *
 * @since  0.0.1
 */
class HtmlView extends BaseHtmlView {
	
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
		$this->params 		= ComponentHelper::getParams('com_oevents');
 
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
		ToolbarHelper::title(Text::_('COM_OEVENTS_MANAGER_OEVENTS'));
		ToolbarHelper::addNew('event.add');
		ToolbarHelper::editList('event.edit');
		ToolbarHelper::deleteList('', 'events.delete');
		ToolbarHelper::custom('events.refresh', 'refresh', 'refresh', 'COM_OEVENTS_REFRESH', false);

		// $canDo = ContentHelper::getActions('com_mywalks');
		// if ($canDo->get('core.create')) { ToolbarHelper::addNew('event.add'); }

		ToolbarHelper::preferences('com_oevents');
	}
}