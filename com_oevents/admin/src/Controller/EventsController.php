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

		$ids = Factory::getApplication()->input->post->get('cid');

		if (empty($ids)) {
			throw new \Exception(Text::_('JERROR_NO_ITEMS_SELECTED'), 500);
		} else {
			$model = $this->getModel();
			$result = $model->deleteEvents($ids);

			if ($result > 0) {
				if ($result == 1) {
					Factory::getApplication()->enqueueMessage('Successfully deleted event', 'message');
				} else {
					$msg = 'Successfully deleted ' . $result . ' events';
					Factory::getApplication()->enqueueMessage($msg, 'message');
				}
			} else {
				Factory::getApplication()->enqueueMessage('Error deleting event', 'error');
			}
		}
		$this->setRedirect('index.php?option='.Factory::getApplication()->input->get->get('option'));
	}

	public function refresh() {
		// Check for request forgeries
		$this->checkToken();

		$updater = new OEventsUpdater();
		$updaterResponse = $updater->refresh();

		$numberOfNewEvents = $updaterResponse['count'];
		$updaterStatus = $updaterResponse['status'];
		
		if (empty($updaterStatus)) {
			Factory::getApplication()->enqueueMessage($numberOfNewEvents . ' events found', 'message');
		} else {
			Factory::getApplication()->enqueueMessage('Error finding events: ' . $updaterStatus, 'warning');
		}

		// Refresh page with message
		$this->setRedirect('index.php?option='.Factory::getApplication()->input->get->get('option'));
	}

}