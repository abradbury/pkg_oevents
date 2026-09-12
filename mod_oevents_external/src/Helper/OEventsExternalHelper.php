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
			// Limit the event name length. The layout escapes it; escaping here would
			// double-escape it, and truncating could cut an HTML entity in half.
			$fullTitle = (string) $result[$i]['title'];
			if (mb_strlen($fullTitle) > $eventNameLimit) {
				$title = mb_substr($fullTitle, 0, $eventNameLimit) . '...';
			} else {
				$title = $fullTitle;
			}

			$result[$i]['title'] = $title;

			// Format the date
			$result[$i]['formattedDate'] = date($dateFormat, strtotime($result[$i]['date']));

			// Only link to web pages, e.g. never to javascript: URLs
			$result[$i]['url']     = self::toWebUrl($result[$i]['url']);
			$result[$i]['clubUrl'] = self::toWebUrl($result[$i]['clubUrl']);
		}
		
		return $result;
	}

	/**
	 * @param   string|null  $url  The URL to check
	 *
	 * @return  string  The URL if it is an http(s) URL, otherwise an empty string
	 */
	private static function toWebUrl($url) {
		$url = trim((string) $url);
		$scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

		return \in_array($scheme, ['http', 'https'], true) ? $url : '';
	}
	
}