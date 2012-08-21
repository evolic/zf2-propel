<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener;

class Module
{
    public function onBootstrap($e)
    {
        $session = $e->getApplication()->getServiceManager()->get('session');
        if (isset($session->lang)) {
            $translator = $e->getApplication()->getServiceManager()->get('translator');
            $translator->setLocale($session->lang);

            $viewModel = $e->getViewModel();
            $viewModel->lang = str_replace('_', '-', $session->lang);
        }
        $eventManager = $e->getApplication()->getEventManager();

        $eventManager->attach('route', function ($e) {
            $lang = $e->getRouteMatch()->getParam('lang');

            // If there is no lang parameter in the route, nothing to do
            if (empty($lang)) {
                return;
            }

            $services = $e->getApplication()->getServiceManager();

            // If the session language is the same, nothing to do
            $session = $services->get('session');
            if (isset($session->lang) && ($session->lang == $lang)) {
                return;
            }

            $viewModel  = $e->getViewModel();
            $translator = $services->get('translator');

            $viewModel->lang = $lang;
            $lang = str_replace('-', '_', $lang);
            $translator->setLocale($lang);
            $session->lang = $lang;
        }, -10);

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
