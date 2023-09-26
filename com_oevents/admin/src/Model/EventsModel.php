<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */

namespace OEvents\Component\OEvents\Administrator\Model;

use \Joomla\CMS\Factory;
use \Joomla\CMS\MVC\Model\ListModel;
use \Joomla\CMS\Component\ComponentHelper;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * OEventsList Model
 *
 * @since  0.0.1
 */
class EventsModel extends ListModel {
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery() {
		// Initialise variables.
		$db    = Factory::getDbo();
		$query = $db->getQuery(true);
 
		$lookAheadMonths = (int)ComponentHelper::getParams('com_oevents')->get('lookAhead');
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
		$db = Factory::getDbo();
		$query = $db->getQuery(true);
		
		$conditions = $db->quoteName('event_id') . ' IN (' . implode(',', $ids) . ')';
		 
		$query->delete($db->quoteName('#__oevents_external'));
		$query->where($conditions);
		 
		$db->setQuery($query);
		$result = $db->execute();  

		return $db->getAffectedRows();                
	}

}
