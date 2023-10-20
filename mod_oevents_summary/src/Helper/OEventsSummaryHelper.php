<?php

namespace OEvents\Module\OEventsSummary\Site\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;
use Joomla\Registry\Registry;

\defined('_JEXEC') or die;

class OEventsSummaryHelper implements DatabaseAwareInterface {
    use DatabaseAwareTrait;

    /**
     * @param   Registry    $params  The module parameters
     */
	public static function getLeadInData(Registry $params) {
		$componentParams = ComponentHelper::getParams('com_oevents');

		// Only get events X months ahead
		$lookAheadMonths = (int)$componentParams->get('lookAhead');
		if ($lookAheadMonths <= 0) {
			$lookAheadMonths = 1;
		}
		$datePlus = date('Y-m-d', strtotime('+' . $lookAheadMonths . ' months', strtotime(date('Y-m-d'))));

		$db = Factory::getDbo();
		$query = $db->getQuery(true)
					->select('*')
					->from($db->quoteName('#__oevents_external'))
					->where('date BETWEEN DATE(NOW()) and ' . $db->quote($datePlus))
					->order('date ASC');

		$count = [];

		try {
			$db->setQuery($query);
            $db->execute();
            $count = $db->getNumRows();
		} catch (\RuntimeException $e) {
			// TODO: Debug log error message so user's can't see
			// Factory::getApplication()->enqueueMessage('Error reading from database for external events', 'message');
		}
		
        return (object) [
            "eventCount" => $count,
            "eventListURL" => $params->get('eventsURL')
        ];
	}
	
}