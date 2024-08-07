<?php

/**
 * OEvents Component
 *
 * @package OEvents.Framework
 **/

namespace OEvents\Library\Updater;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Uri\Uri;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\Component\ComponentHelper;

\defined('_JEXEC') or die();
Factory::getLanguage()->load('com_oevents');

class OEventsUpdater  {

	public function refresh() {
		$this->params = ComponentHelper::getParams('com_oevents');
		$eventLevel = http_build_query(['evt_level' => $this->params->get('eventLevel')]);
		$postcode = str_replace(' ', '', $this->params->get('postcode'));

		$until = new \DateTime('now +'.$this->params->get('lookAhead').' months');
		$untilDay = $until->format('j');
		$untilMonth = $until->format('n');
		$untilYear = $until->format('Y');
		$filterEnd = $until->format('d%2\Fm%2\FY');
		$dateFilter = '&filter_end='.$filterEnd.'&filter_end_year='.$untilYear.'&filter_end_month='.$untilMonth.'&filter_end_day='.$untilDay;
		
		$url = 'https://www.britishorienteering.org.uk/index.php?pg=event&evt_postcode=' . urlencode($postcode) . '&radius=' . $this->params->get('radius') . '&' . $eventLevel . '&bFilter=Filter' . $dateFilter;
		$curlResponse = $this->curl($url);
		$curlErrorMsg = $curlResponse['status'];
		$scraped_page = $curlResponse['data'];

		$newEventsCount = 0;
		if (empty($curlErrorMsg)) {
			// Scraping downloaded data in $scraped_page for content
			$results_page = $this->scrape_between($scraped_page, "<div class=\"event_list", "<footer class=\"footer");
			$separate_results = preg_split("/(<div id=\"evt[0-9]+\" class=\"card mb-3 event_item\">)/", $results_page);

			// Skip the first element as this isn't an event, due to regex issue
			$results = [];
			for ($i=1; $i < sizeof($separate_results); $i++) { 
				$separate_result = $separate_results[$i];

				if (!empty($separate_result)) {
					$result = $this->parseResult($separate_result);

					// Exclude club
					if (strcasecmp($result['club'], $this->params->get('excludedClub')) != 0) {
						$results[] = $result;
					}
				}
			}

			// Add events to database (through model)
			$this->insertExternalEvents($results);

			$newEventsCount = count($results);
		} 
		
		return ['status' => $curlErrorMsg, 'count' => $newEventsCount];
	}

	private function parseResult($separate_result) {
		$result = [];

		// Sometimes there is this in the evt_name div: <span class="closing_soon">Closing date approaching</span>
		$titleTemp = preg_split("/<span.*?/", $this->scrape_between($separate_result, "<strong>", "</strong>"));
		$result['title'] = html_entity_decode($titleTemp[0]);

		$result['date'] = $this->scrape_between($separate_result, "</strong><strong>", "</strong>");
		$result['remote_id'] = (int) $this->scrape_between($separate_result, "data-event-id=\"", "\"");
		$result['venue'] = $this->scrape_between($separate_result, "target=\"_blank\"\">", "</a></div><div");

		$clubTemp = $this->scrape_between($separate_result, "<label>Club:</label>", "</a></div>");
		$result['clubUrl'] = html_entity_decode($this->scrape_between($clubTemp, "href=\"", "\" target="));
		$clubNameTemp = preg_split("/<.*?>/", $clubTemp);
		if (sizeof($clubNameTemp) == 2) {
			$result['club'] = $clubNameTemp[1];
		} else {
			$result['club'] = '';
		}

		$result['level'] = str_replace('&nbsp;', '', $this->scrape_between($separate_result, "<label>Level:</label>", "</div>"));
		$urlTemp = $this->scrape_between(
			$this->scrape_between($separate_result, "<a class=\"btn btn-success\"", ">Full details</a>"),
			"href=\"", "\""
		);
		$result['url'] = "https://www.britishorienteering.org.uk/" . $urlTemp;

		return $result;
	}

	// Defining the basic cURL function
	private function curl($url) {
		// Assigning cURL options to an array
		$options = [
			CURLOPT_RETURNTRANSFER => TRUE,     // Setting cURL's option to return the webpage data
			CURLOPT_FOLLOWLOCATION => TRUE,     // Setting cURL to follow 'location' HTTP headers
			CURLOPT_AUTOREFERER => TRUE,        // Automatically set the referer where following 'location' HTTP headers
			CURLOPT_CONNECTTIMEOUT => 120,      // Setting the amount of time (in seconds) before the request times out
			CURLOPT_TIMEOUT => 120,             // Setting the maximum amount of time for cURL to execute queries
			CURLOPT_MAXREDIRS => 10,            // Setting the maximum number of redirections to follow
			CURLOPT_USERAGENT => 'OEventsBot/2.1.0 (+https://github.com/abradbury, +' . Uri::root() . ')',  // Setting the useragent
			CURLOPT_URL => $url,                // Setting cURL's URL option with the $url variable passed into the function
  		];
			
		$ch = curl_init($url);                  // Initialising cURL 
		curl_setopt_array($ch, $options);       // Setting cURL's options using the previously assigned array data in $options
		$data = curl_exec($ch);                 // Executing the cURL request and assigning the returned data to the $data variable
		$status = curl_error($ch);              // Get error message, if any
		curl_close($ch);                        // Closing cURL        
		
		return ['status' => $status, 'data' => $data];
	}

	// Defining the basic scraping function
	private function scrape_between($data, $start, $end) {
		$data = stristr($data, (string) $start);    // Stripping all data from before $start
		$data = substr($data, strlen($start));  	// Stripping $start
		$stop = stripos($data, (string) $end);      // Getting the position of the $end of the data to scrape
		$data = substr($data, 0, $stop);        	// Stripping all data from after and including the $end of the data to scrape
		return $data;                           	// Returning the scraped data from the function
	}

	private function insertExternalEvents($events) {
		if ((is_countable($events) ? count($events) : 0) > 0) {
			$db = Factory::getDbo();
			$query = $db->getQuery(true);

			$existingEventIDs = $this->getEventIds($db);
			$levelMapping = [
				Text::_("COM_OEVENTS_EVENT_LEVEL_1") => '1', 
				Text::_("COM_OEVENTS_EVENT_LEVEL_2") => '2', 
				Text::_("COM_OEVENTS_EVENT_LEVEL_3") => '3', 
				Text::_("COM_OEVENTS_EVENT_LEVEL_4") => '4', 
				Text::_("COM_OEVENTS_EVENT_LEVEL_5") => '5'
			];

			foreach ($events as $event) {
				$dateTime = \DateTime::createFromFormat('D jS M Y', $event['date'])->format("Y-m-d H:i:s");
				
				$level = "";
				if (array_key_exists($event['level'], $levelMapping)) {
					$level = $levelMapping[$event['level']];
				}

				$newEvent = new \stdClass();
				$newEvent->date     = $dateTime;
				$newEvent->title    = $event['title'];
				$newEvent->venue    = $event['venue'];
				$newEvent->club     = $event['club'];
				$newEvent->level    = $level;
				$newEvent->url      = $event['url'];
				$newEvent->clubUrl  = $event['clubUrl'];
				$newEvent->status   = (int) 1;
				$newEvent->remote_id= (int) $event['remote_id'];

				if (in_array($newEvent->remote_id, $existingEventIDs)) {
					$result = Factory::getDbo()->updateObject('#__oevents_external', $newEvent, 'remote_id');
				} else {
					$result = Factory::getDbo()->insertObject('#__oevents_external', $newEvent);
				}
			}
		}
	}

	private function getEventIds($db) {
		$query = $db->getQuery(true);

		$query->select($db->quoteName('remote_id'));
		$query->from($db->quoteName('#__oevents_external'));
		$query->order('remote_id ASC');

		$db->setQuery($query);
		return $db->loadColumn();
	}
}
