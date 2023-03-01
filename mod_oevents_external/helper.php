<?php

jimport('joomla.application.component.helper');

class ModOEventsExternalHelper {

	public static function getEventsList() {
		// Only get events X months ahead
		$lookAheadMonths = (int)JComponentHelper::getParams('com_oevents')->get('lookAhead');
		if ($lookAheadMonths <= 0) {
			$lookAheadMonths = 1;
		}
		$datePlus = date('Y-m-d', strtotime("+" . $lookAheadMonths . " months", strtotime(date('Y-m-d'))));

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
					->select('*')
					->from($db->quoteName('#__oevents_external'))
					->where('date BETWEEN DATE(NOW()) and ' . $db->quote($datePlus))
					->order('date ASC');

		$result = [];

		try {
			$db->setQuery($query);
			$result = $db->loadAssocList();
		} catch (RuntimeException $e) {
			// FIXME: Running rector with UP_TO_PHP_80 removes the unused variable here, but it causes a runtime error
			// https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md#removeunusedvariableincatchrector

			// TODO: Debug log error message so user's can't see
			// JFactory::getApplication()->enqueueMessage("Error reading from database for external events", 'message');
		}

		// Limit the event name length
		$eventNameLimit = (int)JComponentHelper::getParams('com_oevents')->get('eventNameLimit');

		for ($i=0; $i < sizeof($result); $i++) { 
			$fullTitle = htmlspecialchars((string) $result[$i]['title'], ENT_COMPAT, 'UTF-8');
			if (mb_strlen($fullTitle) > $eventNameLimit) {
				$title = mb_substr($fullTitle, 0, $eventNameLimit) . '...';
			} else {
				$title = $fullTitle;
			}

			$result[$i]['title'] = $title;
		}
		
		return $result;
	}
	
}