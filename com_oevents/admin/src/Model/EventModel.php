<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
namespace OEvents\Component\OEvents\Administrator\Model;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Table\Table;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\MVC\Model\AdminModel;

use OEvents\Component\OEvents\Administrator\Utils\ActionLog;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * OEvents Model
 */
class EventModel extends AdminModel {
	
	/**
     * Method to get a table object, load it if necessary.
     *
     * @param   string  $name     The table name. Optional.
     * @param   string  $prefix   The class prefix. Optional.
     * @param   array   $options  Configuration array for model. Optional.
     *
     * @return  Table  A Table object
     *
     * @since   3.0
     * @throws  \Exception
     */
    public function getTable($name = 'OEvents', $prefix = 'Table', $options = []) {
		if ($table = $this->_createTable($name, $prefix, $options)) {
			return $table;
		}

		throw new \Exception(Text::sprintf('JLIB_APPLICATION_ERROR_TABLE_NAME_NOT_SUPPORTED', $name), 0);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed    A \Joomla\CMS\Form\Form object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = [], $loadData = true) {
		// Get the form.
		$form = $this->loadForm(
			'com_oevents.event',
			'event',
			[
				'control' => 'jform', 
				'load_data' => $loadData
			]
		);
 
		if (empty($form)) {
			return false;
		}
 
		return $form;
	}
 
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState(
			'com_oevents.edit.event.data',
			[]
		);
 
		if (empty($data)) {
			$data = $this->getItem();
		}
 
		return $data;
	}

	/**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success, False on error.
     *
     * @since   1.6
     */
    public function save($data) {
		$result = parent::save($data);

		if ($result) {
			if ($data['event_id'] == '0') {
				ActionLog::recordEventAdded((object) [
					// FIXME: We don't know the event ID at this point (it is 0)
					// So the action log cannot link to the newly-created event.
					// 'id'   => $data['event_id'],
					'name' => $data['title']
				]);
			} else {
				ActionLog::recordEventUpdated((object) [
					'id'   => $data['event_id'],
					'name' => $data['title']
				]);
			}
		}

		return $result;
	}
}