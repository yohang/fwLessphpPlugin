<?php

function fw_include_stylesheets()
{
  echo fw_get_stylesheets();
}

function fw_get_stylesheets()
{
  $response = sfContext::getInstance()->getResponse();

  sfConfig::set('symfony.asset.stylesheets_included', true);

  $html = '';
  foreach ($response->getLesscssParsedStylesheets() as $file => $options)
  {
    $html .= stylesheet_tag($file, $options);
  }

  return $html;
}