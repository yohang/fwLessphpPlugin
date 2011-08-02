<?php

/**
 * If fwLessphp is enabled, include newly parsed stylesheets,
 * else, use the substitute helper
 * 
 * @return void
 */
function fw_include_stylesheets(sfWebResponse $response = null)
{
  echo sfConfig::get('fw_lessphp_enabled', false) ?
         fw_get_stylesheets($response) :
         call_user_func(sfConfig::get('fw_lessphp_substitute_helper', 'get_stylesheets'));
}

/**
 * Get the stylesheets.
 * Parse less files
 *
 * @return string
 */
function fw_get_stylesheets(sfWebResponse $response = null)
{
  sfConfig::set('symfony.asset.stylesheets_included', true);

  $html = '';

  $parsedStylesheets = null;
  if(is_null($response))
  {
    $response = sfContext::getInstance()->getResponse();
    $parsedStylesheets = $response->getLesscssParsedStylesheets();
  }
  else
  {
    $parsedStylesheets = $response->getLesscssParsedStylesheets(sfWebResponse::ALL, false);
  }

  foreach ($parsedStylesheets as $file => $options)
  {
    $html .= stylesheet_tag($file, $options);
  }

  return $html;
}