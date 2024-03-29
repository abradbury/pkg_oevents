<?php

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\Task\OEventsUpdater\Extension\OEventsUpdater;
use Joomla\Utilities\ArrayHelper;

return new class () implements ServiceProviderInterface {

    public function register(Container $container) {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $plugin = new OEventsUpdater(
                    $container->get(DispatcherInterface::class),
                    (array) PluginHelper::getPlugin('task', 'oevents_updater'),
                    ArrayHelper::fromObject(new JConfig()),
                    JPATH_CONFIGURATION . '/configuration.php'
                );
                $plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};
