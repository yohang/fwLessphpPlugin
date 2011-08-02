<?php
/**
 * The fwLessphp Class do some things...
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 */
 
class fwLessphp 
{
  protected
    $sourcePattern,
    $destinationPattern,
    $response,
    $dispatcher;

  public function __construct(sfWebResponse $response, sfEventDispatcher $dispatcher)
  {
    $this->sourcePattern = sfConfig::get('fw_lessphp_source_pattern', '#(.*)\.less#');
    $this->destinationPattern = sfConfig::get('fw_lessphp_destination_pattern', '\1.css');
    $this->response = $response;
    $this->dispatcher = $dispatcher;
  }

  public function getLesscssParsedStylesheets($position = sfWebResponse::ALL)
  {
    $stylesheets = array();
    foreach ($this->response->getStylesheets($position) as $stylesheet => $options)
    {
      if (preg_match($this->sourcePattern, $stylesheet))
      {
        $time = microtime();
        $destinationFile = preg_replace($this->sourcePattern, $this->destinationPattern, $stylesheet);
        $lessCompiler = new lessc;
        file_put_contents(
          $this->getDestinationPath($destinationFile, true),
          $lessCompiler->parse(
            file_get_contents($this->getSourcePath($stylesheet))
          )
        );
        $stylesheets[$this->getDestinationPath($destinationFile)] = $options;

        $event = new sfEvent($this, 'fw_lessphp.render_file', array(
          'less_file'  => sfConfig::get('fw_lessphp_source_base_path', '/data/less') . '/' . $stylesheet,
          'stylesheet' => $this->getDestinationPath($destinationFile),
          'time'       => microtime() - $time,
          'size'       => filesize($this->getDestinationPath($destinationFile, true))
        ));
        $this->dispatcher->notify($event);
      }
      else
      {
        $stylesheets[$stylesheet] = $options;
      }
    }

    return $stylesheets;
  }

  protected function getSourcePath($stylesheet)
  {
    return sfConfig::get('sf_root_dir') .
           sfConfig::get('fw_lessphp_source_base_path', '/data/less') . '/' . $stylesheet;
  }

  protected function getDestinationPath($stylesheet, $absolute = false)
  {
    $path = ($absolute ? sfConfig::get('sf_web_dir') : '') .
            sfConfig::get('fw_lessphp_destination_base_path', '/css') . '/' . $stylesheet;

    if ($absolute)
    {
      $directory = dirname($path);
      if (!is_dir($directory))
      {
        mkdir($directory, 0777, true);
      }
    }

    return $path;
  }

  protected function compile()
  {
    
  }
}
