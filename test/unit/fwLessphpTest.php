<?php

require_once __DIR__ . '/../bootstrap/unit.php';

$t = new lime_test();

$response->addStylesheet('match-to.less');
$response->addStylesheet('match-to.less-to.less');
$response->addStylesheet('dont-match-to-less.css');

try
{
  $parsedStylesheets = $response->getLesscssParsedStylesheets(sfWebResponse::ALL, false);
  $t->pass('Reponse method not found event is intercepted.');
}
catch (Exception $e)
{
  $t->fail('Reponse method not found event is not intercepted.');
}
$t->ok(!isset($parsedStylesheets['match-to.less']), 'Less file 1 is not anymore in the stylesheets');
$t->ok(!isset($parsedStylesheets['match-to.less-to.less']), 'Less file 2 is not anymore in the stylesheets');
$t->ok(isset($parsedStylesheets['dont-match-to-less.css']), 'Classic css file isn\'t matched');
$t->ok(isset($parsedStylesheets['/less/cache/match-to.css']), 'Less file 1 stylesheet is here');
$t->ok(isset($parsedStylesheets['/less/cache/match-to.less-to.css']), 'Less file 2 stylesheet is here');


