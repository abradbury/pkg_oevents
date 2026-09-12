<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
*/

namespace OEvents\Component\OEvents\Administrator\Controller;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\MVC\Model\BaseDatabaseModel;
use \Joomla\CMS\MVC\Controller\AdminController;

use OEvents\Library\Updater\OEventsUpdater;
use OEvents\Component\OEvents\Administrator\Utils\ActionLog;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * OEvents Controller
 */
class EventsController extends AdminController {

    /**
     * The default view for the display method.
     *
     * @var string
     */
    protected $default_view = 'events';

    public function display($cachable = false, $urlparams = array()) {
        return parent::display($cachable, $urlparams);
    }

    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The name of the model.
     * @param   string  $prefix  The prefix for the PHP class name.
     * @param   array   $config  Array of configuration parameters.
     *
     * @return  BaseDatabaseModel
     *
     * @since   1.6
     */
    public function getModel($name = 'Events', $prefix = 'Administrator', $config = ['ignore_request' => true]) {
        return parent::getModel($name, $prefix, $config);
    }

	public function delete() {
		// Check for request forgeries
        $this->checkToken();

		if (!Factory::getApplication()->getIdentity()->authorise('core.delete', 'com_oevents')) {
			Factory::getApplication()->enqueueMessage(Text::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), 'error');
			$this->setRedirect('index.php?option='.Factory::getApplication()->getInput()->get->get('option'));
			return;
		}

		$ids = array_filter((array) Factory::getApplication()->getInput()->post->get('cid', [], 'int'));

		if (empty($ids)) {
			throw new \Exception(Text::_('JERROR_NO_ITEMS_SELECTED'), 500);
		} else {
			$model = $this->getModel();
			$events = $this->getEvents($ids); // So we can log what has been deleted
			$result = $model->deleteEvents($ids);

			if ($result > 0) {
				// NOTE: Here we are iterating through the events we wanted to delete,
				// which might not be the same as the events that were actually deleted.
				// Investigate what the value of $result will be if some events were
				// deleted and some events were not.
				foreach ($events as $event) {
					ActionLog::recordEventDeleted((object) [
						'id'   => $event->event_id,
						'name' => $event->title
					]);

					Factory::getApplication()->enqueueMessage('Successfully deleted event: ' . $event->title, 'message');
				}
			} else {
				Factory::getApplication()->enqueueMessage('Error deleting event(s)', 'error');
			}
		}
		$this->setRedirect('index.php?option='.Factory::getApplication()->getInput()->get->get('option'));
	}

	private function getEvents($ids) {
		/* @var OEventsModelEvent $model */
		$model = BaseDatabaseModel::getInstance('Event', 'OEventsModel');

		$events = array();
		foreach ($ids as $id) {
			array_push($events, $model->getItem($id));
		}

		return $events;
	}

	public function refresh() {
		// Check for request forgeries
		$this->checkToken();

		// A refresh adds events, so it requires the create permission
		if (!Factory::getApplication()->getIdentity()->authorise('core.create', 'com_oevents')) {
			Factory::getApplication()->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
			$this->setRedirect('index.php?option='.Factory::getApplication()->getInput()->get->get('option'));
			return;
		}

		$updater = new OEventsUpdater();
		$updaterResponse = $updater->refresh();

		$numberOfNewEvents = $updaterResponse['count'];
		$updaterStatus = $updaterResponse['status'];
		
		if (empty($updaterStatus)) {
			ActionLog::recordManualRefresh();
			Factory::getApplication()->enqueueMessage($numberOfNewEvents . ' events found', 'message');
		} else {
			Factory::getApplication()->enqueueMessage('Error finding events: ' . $updaterStatus, 'warning');
		}

		// Refresh page with message
		$this->setRedirect('index.php?option='.Factory::getApplication()->getInput()->get->get('option'));
	}

}