<?php
 
// No direct access
defined('_JEXEC') or die;
// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';

JFactory::getLanguage()->load('com_oevents');

$eventList = modOEventsExternalHelper::getEventsList();

require JModuleHelper::getLayoutPath('mod_oevents_external');
