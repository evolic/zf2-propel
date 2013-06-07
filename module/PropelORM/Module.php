<?php

namespace PropelORM;

use Zend\ModuleManager\ModuleManager;

class Module
{
    public function init(ModuleManager $moduleManager)
    {
        \Propel::init(realpath(__DIR__ . '/config/zf2-propel-conf.php'));
        set_include_path(realpath(__DIR__ . '/src/PropelORM/Model') . PATH_SEPARATOR . get_include_path());
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
         );
    }
}