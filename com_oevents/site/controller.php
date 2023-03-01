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
			// The salt and password placeholders below will be replaced with generated 
			// values when the create_and_install_package.sh script is run. See the 
			// project's README file for more information.
			$quay = "\$1\$Mao0YZIRtNHnfUHGCmni3Mtg\$" . $quay;

			if (!password_verify("ObnbMf8yojoio04jkwKcG1Rd".$dayOfYear, $quay)) {
				die( JText::_( 'Invalid Token' ) );
			}
		}

		$postData->set(JSession::getFormToken(), '=1');
		$app->input->post = $postData;
	}

}
