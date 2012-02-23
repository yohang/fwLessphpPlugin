<?php
/**
 * The fwLessphp Manage rendering of less files
 *
 * Warning : This class (and this plugin) is NOT DESIGNED to be used in PRODUCTION !
 *
 * You'd better to use it in dev mode, and to use the swCombinePlugin less driver in production
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

  /**
   * @param string $position
   * @param bool $write
   * @return array
   */
  public function getLesscssParsedStylesheets($position = sfWebResponse::ALL, $write = true)
  {
    $stylesheets = array();

    // Browse all the response stylesheets
    foreach ($this->response->getStylesheets($position) as $stylesheet => $options)
    {
      // If the stylesheet matches the less pattern
      if (preg_match($this->sourcePattern, $stylesheet))
      {
        $time = microtime();

        // compile it
        $destinationFile = preg_replace($this->sourcePattern, $this->destinationPattern, $stylesheet);
        $lessCompiler = new lessc;
        $lessCompiler->importDir = dirname($this->getSourcePath($stylesheet));

        // Write it if necessary
        if ($write) {
          file_put_contents(
            $this->getDestinationPath($destinationFile, true),
            $lessCompiler->parse(
              file_get_contents($this->getSourcePath($stylesheet))
            )
          );
        }

        // store new stylesheet path
        $stylesheets[$this->getDestinationPath($destinationFile)] = $options;

        // Raise an event with the compiling infos (used for web debuf toolbar)
        $event = new sfEvent($this, 'fw_lessphp.render_file', array(
          'less_file'  => sfConfig::get('fw_lessphp_source_base_path', '/data/less') . '/' . $stylesheet,
          'stylesheet' => $this->getDestinationPath($destinationFile),
          'time'       => microtime() - $time,
          'size'       => filesize($this->getDestinationPath($destinationFile, true, $write))
        ));
        $this->dispatcher->notify($event);
      }
      else
      {
        // If the file isn't a less one, just add it to the stylesheets
        $stylesheets[$stylesheet] = $options;
      }
    }

    return $stylesheets;
  }

  /**
   * Returns the source path of a stylesheet
   *
   * @param $stylesheet
   * @return string
   */
  protected function getSourcePath($stylesheet)
  {
    return sfConfig::get('sf_root_dir') .
           sfConfig::get('fw_lessphp_source_base_path', '/data/less') . '/' . $stylesheet;
  }

  /**
   * Returns the destination path of the stylesheet, create directory if necessary
   * 
   * @param $stylesheet
   * @param bool $absolute
   * @return string
   */
  protected function getDestinationPath($stylesheet, $absolute = false, $write = true)
  {
    $path = ($absolute ? sfConfig::get('sf_web_dir') : '') .
            sfConfig::get('fw_lessphp_destination_base_path', '/css') . '/' . $stylesheet;

    if ($absolute)
    {
      $directory = dirname($path);
      if (!is_dir($directory) && $write)
      {
        mkdir($directory, 0777, true);
      }
    }

    return $path;
  }

}
