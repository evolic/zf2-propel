<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;

class Module
{
    public function onBootstrap($e)
    {
        $translator = $e->getApplication()->getServiceManager()->get('translator');
        $session    = $e->getApplication()->getServiceManager()->get('session');
        if (isset($session->lang)) {
            $translator->setLocale($session->lang);
        }
        
        $viewModel           = $e->getApplication()->getMvcEvent()->getViewModel();
        $viewModel->lang     = $translator->getLocale();
        
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

}
