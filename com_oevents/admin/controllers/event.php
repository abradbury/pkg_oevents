<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevent
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * OEvents Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_oevent
 */
class OEventsControllerEvent extends JControllerForm {
	
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Event', $prefix = 'OEventsModel', $config = ['ignore_request' => true]) {
		$model = parent::getModel($name, $prefix, $config);
 
		return $model;
	}
}