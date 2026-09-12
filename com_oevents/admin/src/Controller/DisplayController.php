<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
namespace OEvents\Component\OEvents\Administrator\Controller; 

use \Joomla\CMS\Router\Route;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\MVC\Controller\BaseController;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * General Controller of OEvents component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 * @since       1.0.0
 */
class DisplayController extends BaseController {

	/**
	 * The default view for the display method.
	 *
	 * @var string
	 */
	protected $default_view = 'events';

	/**
	 * Method to display a view.
	 *
	 * The edit form may only be reached through the event.add/event.edit tasks,
	 * which check the user's create/edit permissions before holding the ID.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe URL parameters and their variable types.
	 *
	 * @return  static|boolean  This object to support chaining, or false on failure.
	 */
	public function display($cachable = false, $urlparams = []) {
		$view   = $this->input->get('view', $this->default_view);
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('event_id');

		if ($view === 'event' && $layout === 'edit' && !$this->checkEditId('com_oevents.edit.event', $id)) {
			// Somehow the person just went to the form - we don't allow that.
			if (!count($this->app->getMessageQueue())) {
				$this->setMessage(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id), 'error');
			}

			$this->setRedirect(Route::_('index.php?option=com_oevents&view=events', false));

			return false;
		}

		return parent::display($cachable, $urlparams);
	}

}