<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_oevents
 */

namespace OEvents\Component\OEvents\Administrator\Utils;

use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;
use \Joomla\CMS\MVC\Model\BaseDatabaseModel;

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class ActionLog {

    public static function recordEventAdded($event) {
        return ActionLog::recordEventAction($event, 'COM_OEVENTS_ACTIONLOG_CONTENT_ADDED');
    }

    public static function recordEventUpdated($event) {
        return ActionLog::recordEventAction($event, 'PLG_SYSTEM_ACTIONLOGS_CONTENT_UPDATED');
    }

    public static function recordEventDeleted($event) {
        return ActionLog::recordEventAction($event, 'PLG_SYSTEM_ACTIONLOGS_CONTENT_DELETED');
    }

    private static function recordEventAction($event, $messageLanguageKey) {
        $user = Factory::getUser();

        $message = array(
			'id'          => $event->id,
			'title'       => $event->name,
            'type'        => Text::_('COM_OEVENTS_ACTIONLOG_TYPE'),
			'itemlink'    => 'index.php?option=com_oevents&task=event.edit&event_id=' . $event->id,
            
			'userid'      => $user->id,
			'username'    => $user->name,
			'accountlink' => 'index.php?option=com_users&task=user.edit&id=' . $user->id,
		);

        return ActionLog::recordActionLog($message, $messageLanguageKey, $user);
    }

    public static function recordManualRefresh() {
        $user = Factory::getUser();

        $message = array(
			'userid'      => $user->id,
			'username'    => $user->name,
			'accountlink' => 'index.php?option=com_users&task=user.edit&id=' . $user->id,
		);

        return ActionLog::recordActionLog($message, "COM_OEVENTS_ACTIONLOG_MANUAL_REFRESH", $user);
    }

    private static function recordActionLog($message, $messageLanguageKey, $user) {
		/* @var ActionlogsModelActionlog $model */
		$model = BaseDatabaseModel::getInstance('Actionlog', 'ActionlogsModel');
        $model->addLog(array($message), $messageLanguageKey, 'com_oevents', $user->id);

        return true;
    }
}
