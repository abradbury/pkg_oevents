<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.helper');
 
/**
 * OEventsList Model
 *
 * @since  0.0.1
 */
class OEventsModelEvents extends JModelList {
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery() {
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
 
		$lookAheadMonths = (int)JComponentHelper::getParams('com_oevents')->get('lookAhead');
		if ($lookAheadMonths <= 0) {
			$lookAheadMonths = 1;
		}
		$datePlus = date('Y-m-d', strtotime('+' . $lookAheadMonths . ' months', strtotime(date('Y-m-d'))));

		// Create the base select statement.
		$query->select('*')
				->from($db->quoteName('#__oevents_external'))
				->where('date BETWEEN DATE(NOW()) and ' . $db->quote($datePlus))
				->order('date ASC');
 
		return $query;
	}

	public function deleteEvents($ids) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$conditions = $db->quoteName('event_id') . ' IN (' . implode(',', $ids) . ')';
		 
		$query->delete($db->quoteName('#__oevents_external'));
		$query->where($conditions);
		 
		$db->setQuery($query);
		$result = $db->execute();  

		return $db->getAffectedRows();                
	}

}
