<?php

namespace OEvents\Module\OEventsExternal\Site\Helper;

use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Database\DatabaseAwareInterface;
use Joomla\Database\DatabaseAwareTrait;

\defined('_JEXEC') or die;

class OEventsExternalHelper implements DatabaseAwareInterface {
    use DatabaseAwareTrait;

	public static function getEventsList() {
		$params = ComponentHelper::getParams('com_oevents');

		// Only get events X months ahead
		$lookAheadMonths = (int)$params->get('lookAhead');
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

		$result = [];

		try {
			$db->setQuery($query);
			$result = $db->loadAssocList();
		} catch (\RuntimeException $e) {
			// TODO: Debug log error message so user's can't see
			// Factory::getApplication()->enqueueMessage('Error reading from database for external events', 'message');
		}

		$eventNameLimit = (int)$params->get('eventNameLimit');
		$dateFormat = $params->get('dateFormat');

		for ($i=0; $i < sizeof($result); $i++) { 
			// Limit the event name length
			$fullTitle = htmlspecialchars((string) $result[$i]['title'], ENT_COMPAT, 'UTF-8');
			if (mb_strlen($fullTitle) > $eventNameLimit) {
				$title = mb_substr($fullTitle, 0, $eventNameLimit) . '...';
			} else {
				$title = $fullTitle;
			}

			$result[$i]['title'] = $title;

			// Format the date
			$result[$i]['formattedDate'] = date($dateFormat, strtotime($result[$i]['date']));
		}
		
		return $result;
	}
	
}