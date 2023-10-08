<?php

namespace OEvents\Module\OEventsExternal\Site\Dispatcher;

use Joomla\CMS\Factory;
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

\defined('_JEXEC') or die;

class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface {
    use HelperFactoryAwareTrait;

    protected function getLayoutData(): array {
        Factory::getLanguage()->load('com_oevents');

        $data = parent::getLayoutData();
        $data['events'] = $this->getHelperFactory()->getHelper('OEventsExternalHelper')->getEventsList();

        return $data;
    }
}
