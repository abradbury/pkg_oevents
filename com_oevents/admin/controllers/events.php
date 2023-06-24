<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');

/**
 * OEvents Controller
 */
class OEventsControllerEvents extends JControllerAdmin {
	
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Events', $prefix = 'OEventsModel', $config = ['ignore_request' => true]) {
		$model = parent::getModel($name, $prefix, $config);
 
		return $model;
	}

	private function addExternalEvents($events) {
		$model = $this->getModel();
		$result = $model->insertExternalEvents($events);
	}

	public function delete() {
		// JRequest::checkToken() or die( JText::_( 'Invalid Token' ) );
		$ids = JFactory::getApplication()->input->post->get('cid');

		if (empty($ids)) {
			throw new \Exception(JText::_('JERROR_NO_ITEMS_SELECTED'), 500);
		} else {
			$model = $this->getModel();
			$result = $model->deleteEvents($ids);

			if ($result > 0) {
				if ($result == 1) {
					JFactory::getApplication()->enqueueMessage('Successfully deleted event', 'message');
				} else {
					$msg = 'Successfully deleted ' . $result . ' events';
					JFactory::getApplication()->enqueueMessage($msg, 'message');
				}
			} else {
				JFactory::getApplication()->enqueueMessage('Error deleting event', 'error');
			}
		}
		$this->setRedirect('index.php?option='.JFactory::getApplication()->input->get->get('option'));
	}

	public function refresh() {
		$updater = new OEventsUpdater();
		$updaterResponse = $updater->refresh();

		$numberOfNewEvents = $updaterResponse['count'];
		$updaterStatus = $updaterResponse['status'];
		
		if (empty($updaterStatus)) {
			JFactory::getApplication()->enqueueMessage($numberOfNewEvents . ' events found', 'message');
		} else {
			JFactory::getApplication()->enqueueMessage('Error finding events: ' . $updaterStatus, 'warning');
		}

		// Refresh page with message
		$this->setRedirect('index.php?option='.JFactory::getApplication()->input->get->get('option'));
	}

}