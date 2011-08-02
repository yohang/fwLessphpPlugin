<?php

/**
 * If fwLessphp is enabled, include newly parsed stylesheets,
 * else, use the substitute helper
 * 
 * @return void
 */
function fw_include_stylesheets()
{
  echo sfConfig::get('fw_lessphp_enabled', false) ?
         fw_get_stylesheets() :
         call_user_func(sfConfig::get('fw_lessphp_substitute_helper', 'get_stylesheets'));
}

/**
 * Get the stylesheets.
 * Parse less files
 *
 * @return string
 */
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