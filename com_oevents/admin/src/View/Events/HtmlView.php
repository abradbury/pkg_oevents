<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
namespace OEvents\Component\OEvents\Administrator\View\Events;

use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Toolbar\ToolbarHelper;
use \Joomla\CMS\Helper\ContentHelper;
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
	 * The actions the current user is permitted to perform on com_oevents.
	 *
	 * @var  object
	 */
	protected $canDo;

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
		$this->canDo		= ContentHelper::getActions('com_oevents');
 
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

		if ($this->canDo->get('core.create')) {
			ToolbarHelper::addNew('event.add');
		}

		if ($this->canDo->get('core.edit')) {
			ToolbarHelper::editList('event.edit');
		}

		if ($this->canDo->get('core.delete')) {
			ToolbarHelper::deleteList('', 'events.delete');
		}

		// A refresh adds events, so it requires the create permission
		if ($this->canDo->get('core.create')) {
			ToolbarHelper::custom('events.refresh', 'refresh', 'refresh', 'COM_OEVENTS_REFRESH', false);
		}

		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options')) {
			ToolbarHelper::preferences('com_oevents');
		}
	}
}