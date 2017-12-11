<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * OEvents Table class
 *
 * @since  1.0.0
 */
class OEventsTableOEvents extends JTable {
	
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__oevents_external', 'event_id', $db);
	}
}