<?php

require_once __DIR__ . '/../../bootstrap/unit.php';

$t = new lime_test();

$response->addStylesheet('match-to.less');
$response->addStylesheet('match-to.less-to.less');
$response->addStylesheet('dont-match-to-less.css');

$fwLessphp->getLesscssParsedStylesheets(sfWebResponse::ALL, false);

$t->is(count($configuration->getWebDebug()->getInfos()), 2, 'Debug class receive and save the good informations');

