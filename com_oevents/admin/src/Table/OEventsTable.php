<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */

namespace OEvents\Component\OEvents\Administrator\Table;

use \Joomla\CMS\Table\Table;
use \Joomla\Database\DatabaseDriver;

// No direct access
defined('_JEXEC') or die('Restricted access');
 
/**
 * OEvents Table class
 *
 * @since  1.0.0
 */
class OEventsTable extends Table {
	
	/**
	 * Constructor
	 *
	 * @param   DatabaseDriver  &$db  A database connector object
	 */
	function __construct(&$db) {
		parent::__construct('#__oevents_external', 'event_id', $db);
	}
}