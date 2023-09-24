<?php

/**
 * @package     Joomla.Plugins
 * @subpackage  Task.OEventsUpdater
 */

namespace Joomla\Plugin\Task\OEventsUpdater\Extension;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Component\Scheduler\Administrator\Event\ExecuteTaskEvent;
use Joomla\Component\Scheduler\Administrator\Traits\TaskPluginTrait;
use Joomla\Event\SubscriberInterface;

use OEvents\Library\Updater\OEventsUpdater as OEventsUpdaterLib;

// no direct access
defined('_JEXEC') or die;

final class OEventsUpdater extends CMSPlugin implements SubscriberInterface {
	use TaskPluginTrait;

	protected const TASKS_MAP = [
		'plg_task_oevents_updater' => [
			'langConstPrefix' => 'PLG_TASK_OEVENTS_UPDATER',
			'method' 		  => 'myFunction',
		],
	];

	protected $autoloadLanguage = true;

	public static function getSubscribedEvents(): array {
		return [
			'onTaskOptionsList'    => 'advertiseRoutines',
			'onExecuteTask'        => 'update',
		];
	}

	public function update(ExecuteTaskEvent $event): void {
		if (!array_key_exists($event->getRoutineId(), self::TASKS_MAP)) {
			return;
		}

		$this->startRoutine($event);
		$this->logTask("About to trigger OEvents update...");
		$exit = 0;
	
		try {
			$updater = new OEventsUpdaterLib();
			$updaterResponse = $updater->refresh();
			$this->logTask("Successfully triggered OEvents update: " . var_export($updaterResponse, true));
			$exit = 0;

		} catch (\Throwable $th) {
			$this->logTask("Error triggering OEvents update: " . $th);
			$exit = 1;
		}

		$this->endRoutine($event, $exit);
	}
}
