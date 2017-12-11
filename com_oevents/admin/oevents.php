<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Get common code from library
jimport('oevents.updater');
 
// Get an instance of the controller prefixed by OEvents
$controller = JControllerLegacy::getInstance('OEvents');
 
// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));
 
// Redirect if set by the controller
$controller->redirect();