<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */
 
namespace OEvents\Component\OEvents\Administrator\Controller; 

use \Joomla\CMS\MVC\Controller\BaseController;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
/**
 * General Controller of OEvents component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 * @since       1.0.0
 */
class DisplayController extends BaseController {
	
	/**
	 * The default view for the display method.
	 *
	 * @var string
	 */
	protected $default_view = 'events';
	
}