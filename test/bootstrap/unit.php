<?php

require_once __DIR__.'/../../../../test/bootstrap/unit.php';
require_once __DIR__.'/../../lib/debug/fwWebDebugPanelLessphp.class.php';

sfConfig::add(array(
  'fw_lessphp_enabled' => true,
  'fw_lessphp_substitute_helper' => 'sw_get_stylesheets',
  'fw_lessphp_source_pattern' => '#(.*)\\.less#',
  'fw_lessphp_source_base_path' => '/data/less',
  'fw_lessphp_destination_pattern' => '\\1.css',
  'fw_lessphp_destination_base_path' => '/less/cache',
));

$dispatcher = new sfEventDispatcher();
$configuration = new fwLessphpPluginConfiguration(new sfProjectConfiguration(__DIR__.'/../../../../', $dispatcher));
$response = new sfWebResponse($dispatcher);
$fwLessphp = new fwLessphp($response, $dispatcher);
$configuration->setFwLessphp($fwLessphp);
$webDebug = new sfWebDebug($dispatcher, new sfVarLogger($dispatcher));
$configuration->setWebDebug(new fwWebDebugPanelLessphp($webDebug));
