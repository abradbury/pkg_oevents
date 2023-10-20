<?php

namespace OEvents\Module\OEventsSummary\Site\Dispatcher;

use Joomla\CMS\Factory;
use Joomla\CMS\Dispatcher\AbstractModuleDispatcher;
use Joomla\CMS\Helper\HelperFactoryAwareInterface;
use Joomla\CMS\Helper\HelperFactoryAwareTrait;

\defined('_JEXEC') or die;

class Dispatcher extends AbstractModuleDispatcher implements HelperFactoryAwareInterface {
    use HelperFactoryAwareTrait;

    protected function getLayoutData(): array {
        $data = parent::getLayoutData();
        $data['leadIn'] = $this->getHelperFactory()->getHelper('OEventsSummaryHelper')->getLeadInData($data['params']);

        return $data;
    }
}
