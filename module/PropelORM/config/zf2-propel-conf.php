<?php
// This file generated by Propel 1.6.9 convert-conf target
// from XML runtime conf file C:\xampp\projects\zf2-propel\module\PropelORM\runtime-conf.xml
$conf = array (
  'datasources' => 
  array (
    'zf2tutorial-blog' => 
    array (
      'adapter' => 'mysql',
      'connection' => 
      array (
        'dsn' => 'mysql:host=localhost;dbname=zf2tutorial-blog',
        'user' => 'root',
        'password' => '',
      ),
    ),
    'default' => 'zf2tutorial-blog',
  ),
  'log' => 
  array (
    'ident' => 'zf2-propel',
    'level' => '7',
  ),
  'generator_version' => '1.6.9',
);
$conf['classmap'] = include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classmap-zf2-propel-conf.php');
return $conf;