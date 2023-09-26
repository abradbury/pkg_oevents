<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */

namespace OEvents\Component\OEvents\Administrator\Controller;

use \Joomla\CMS\MVC\Model\BaseDatabaseModel;
use \Joomla\CMS\MVC\Controller\FormController;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * OEvents Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
class EventController extends FormController {
	
    /**
     * Proxy for getModel.
     *
     * @param   string  $name    The name of the model.
     * @param   string  $prefix  The prefix for the PHP class name.
     * @param   array   $config  Array of configuration parameters.
     *
     * @return  BaseDatabaseModel
     *
     * @since   1.6
     */
    public function getModel($name = 'Event', $prefix = 'Administrator', $config = ['ignore_request' => true]) {
        return parent::getModel($name, $prefix, $config);
    }
}