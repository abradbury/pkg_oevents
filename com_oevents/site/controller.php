<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * OEvents Component Controller
 *
 * @since  1.0.0
 */
class OEventsController extends JControllerLegacy {

	public function refresh() {
		$this->securityCheck();

		$updater = new OEventsUpdater();
		$updaterResponse = $updater->refresh();

		$this->setRedirect(JRoute::_(JURI::base()));
	}

	private function securityCheck() {
		$app = JFactory::getApplication();
		$postData = $app->input->post;
		$quay = $postData->get('quay', '', 'RAW');
	   	$dayOfYear = date('z') + 1;

		if (empty($quay)) {
			die( JText::_( 'Invalid Token' ) );
		} else {
			$quay = "\$1\$oATIsO6NzxngehUs7x3ghdB+\$" . $quay;

			if (password_verify("jatBpOLiq7x+v3cfSuNCEmzF".$dayOfYear, $quay)) {
			} else {
				die( JText::_( 'Invalid Token' ) );
			}
		}

		$postData->set(JSession::getFormToken(), '=1');
		$app->input->post = $postData;
	}

}
