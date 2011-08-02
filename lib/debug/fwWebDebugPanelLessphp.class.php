<?php
/**
 * Web debug toolbar less php class
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 */
 
class fwWebDebugPanelLessphp extends sfWebDebugPanel
{
  protected $infos = array();

  public function __construct(sfWebDebug $webDebug)
  {
    parent::__construct($webDebug);

    $this->webDebug->getEventDispatcher()->connect('fw_lessphp.render_file', array($this, 'listenToFwLessphpRenderFileEvent'));
  }

  /**
   * Gets the text for the link.
   *
   * @return string The link text
   */
  public function getTitle()
  {
    return '<img src="/fwLessphpPlugin/images/less_css_logo.png" alt="lessphp" /> Lessphp';
  }

  /**
   * Gets the title of the panel.
   *
   * @return string The panel title
   */
  public function getPanelTitle()
  {
    return 'Lessphp Details';
  }

  /**
   * Gets the panel HTML content.
   *
   * @return string The panel HTML content
   */
  public function getPanelContent()
  {
    $content = '';

    foreach ($this->infos as $info)
    {
      $content .= $this->renderElement($info);
    }

    return $content;
  }

  /**
   * Returns the HTML code for an element
   *
   * @param $parameters
   * @return string
   */
  protected function renderElement($parameters)
  {
    $time = round($parameters['time'] * 1000);
    $size = round($parameters['size'] / 1024, 2);
    $id = uniqid();
    
    return sprintf(
      <<<HTML
        <h2>
          %s
          <a title="Toggle Details" onclick="sfWebDebugToggle('%s'); return false;">
            <img alt="Toggle details" src="/sf/sf_web_debug/images/toggle.gif" />
          </a>
        </h2>
        <div id="%s" style="display: none; margin-bottom: 5px;">
          <strong>Rendered as : </strong> <a href="%s">%s</a> <br />
          <strong>Filesize : </strong> %sko<br />
          <strong>Time : </strong> %sms
        </div>
HTML
      , $parameters['less_file'], $id, $id, $parameters['stylesheet'], $parameters['stylesheet'], $size, $time
    );
  }

  /**
   * Listen to the render_file event, to get less files infos
   *
   * @param sfEvent $event
   * @return void
   */
  public function listenToFwLessphpRenderFileEvent(sfEvent $event)
  {
    $this->infos[] = $event->getParameters();
  }

}
