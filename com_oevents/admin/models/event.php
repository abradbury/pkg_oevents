<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * OEvents Model
 */
class OEventsModelEvent extends JModelAdmin {
	
	/**
	 * Method to get a table object, load it if necessary.
	 *
	 * @param   string  $type    The table name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A JTable object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'OEvents', $prefix = 'OEventsTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}
 
	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed    A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// Get the form.
		$form = $this->loadForm(
			'com_oevents.event',
			'event',
			array(
				'control' => 'jform',
				'load_data' => $loadData
			)
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
		$data = JFactory::getApplication()->getUserState(
			'com_oevents.edit.event.data',
			array()
		);
 
		if (empty($data)) {
			$data = $this->getItem();
		}
 
		return $data;
	}
}