<?php

use \Joomla\CMS\Factory;
use Joomla\CMS\Installer\InstallerAdapter;

class com_oeventsInstallerScript
{
	/**
	 * Constructor
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 */
	public function __construct(InstallerAdapter $adapter)
	{
	}

	/**
	 * Called before any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($route, InstallerAdapter $adapter)
	{
		return true;
	}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($route, $adapter)
	{
		return true;
	}

	/**
	 * Called on installation
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function install(InstallerAdapter $adapter)
	{
		return $this->maybeSetupUserActions();
	}

	/**
	 * Called on update
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function update(InstallerAdapter $adapter)
	{
		return $this->maybeSetupUserActions();
	}

	/**
	 * Called on uninstallation
	 *
	 * @param   InstallerAdapter  $adapter  The object responsible for running this script
	 */
	public function uninstall(InstallerAdapter $adapter)
	{
		return true;
	}

	private function maybeSetupUserActions()
	{
		$extension = 'com_oevents';
		$db = Factory::getDbo();

		// First we must check if it is already in the table or not...
		// because of limitations in the library not doing this itself
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__action_logs_extensions');
		$query->where($db->quoteName('extension') . ' = ' . $db->Quote($extension));
		$db->setQuery($query);

		try {
			$result = $db->loadObjectList();

			if (!$result) {
				$db->setQuery(' INSERT into #__action_logs_extensions (extension) VALUES (' . $db->Quote($extension) . ')');
				$db->execute();
			}

			return $this->maybeAddUserActionType($db, $extension);

		} catch (\RuntimeException $e) {
			Factory::getApplication()->enqueueMessage($e->getMessage());
			return false;
		}
	}

	private function maybeAddUserActionType($db, $extension)
	{
		$logConf = new \stdClass();
		$logConf->id = 0;
		$logConf->type_title = 'event';
		$logConf->type_alias = $extension;
		$logConf->id_holder = 'event_id';
		$logConf->title_holder = 'title';
		$logConf->table_name = '#__oevents_external';
		$logConf->text_prefix = 'COM_OEVENTS_ACTIONLOG';

		// First we must check if it is already in the table or not...
		// because of limitations in the library not doing this itself
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__action_log_config');
		$query->where($db->quoteName('type_alias') . ' = ' . $db->Quote($extension));
		$db->setQuery($query);

		try {
			$result = $db->loadObjectList();

			if (!$result) {
				$result = $db->insertObject('#__action_log_config', $logConf, 'id');
				return true;
			}
		} catch (\RuntimeException $e) {
			Factory::getApplication()->enqueueMessage($e->getMessage());
			return false;
		}
	}
}

?>