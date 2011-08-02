<?php

require_once __DIR__ . '/../../bootstrap/unit.php';
require_once __DIR__ . '/../../../lib/fwLessphp.class.php';
require_once __DIR__ . '/../../../lib/helper/fwLessphpHelper.php';
require_once sfConfig::get('sf_symfony_lib_dir').'/../test/unit/sfContextMock.class.php';
require_once sfConfig::get('sf_symfony_lib_dir').'/helper/TagHelper.php';
require_once sfConfig::get('sf_symfony_lib_dir').'/helper/UrlHelper.php';
require_once sfConfig::get('sf_symfony_lib_dir').'/helper/AssetHelper.php';

class myRequest
{
  public $relativeUrlRoot = '';

  public function getRelativeUrlRoot()
  {
    return $this->relativeUrlRoot;
  }

  public function isSecure()
  {
    return false;
  }

  public function getHost()
  {
    return 'localhost';
  }
}

class myController
{
  public function genUrl($parameters = array(), $absolute = false)
  {
    return ($absolute ? '/' : '').$parameters;
  }
}

$context = sfContext::getInstance(array('request' => 'myRequest', 'response' => 'sfWebResponse', 'controller' => 'myController'));

$t = new lime_test();

$response->addStylesheet('match-to.less');
$response->addStylesheet('match-to.less-to.less');
$response->addStylesheet('dont-match-to-less.css');

$parsedStylesheet = $fwLessphp->getLesscssParsedStylesheets(sfWebResponse::ALL, false);
$stylesheetHtml = fw_get_stylesheets($response);

$expectedHtml = <<<HTML
<link rel="stylesheet" type="text/css" media="screen" href="/less/cache/match-to.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/less/cache/match-to.less-to.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/css/dont-match-to-less.css" />

HTML;

$t->is($stylesheetHtml, $expectedHtml, 'fw_get_stylesheets helper return the good stylesheets.');

ob_start();
fw_include_stylesheets($response);
$t->is(ob_get_clean(), $expectedHtml, 'fw_include_stylesheets display the good stylesheets');
