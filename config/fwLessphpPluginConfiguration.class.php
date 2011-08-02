<?php
/**
 * The fwLessphpPluginConfiguration Class represent the configuration of fwLessphpPlugin
 *
 * @author Yohan Giarelli <yohan@giarelli.org>
 */
 
class fwLessphpPluginConfiguration extends sfPluginConfiguration
{
  /**
   * @var fwLessphp
   */
  protected $fwLessphp;

  public function configure()
  {
    $this->dispatcher->connect('context.load_factories', array($this, 'listenToContextLoadFactoriesEvent'));
    $this->dispatcher->connect('response.method_not_found', array($this, 'listenToResponseMethodNotFoundEvent'));
    $this->dispatcher->connect('debug.web.load_panels', array($this, 'listenToDebugWebLoadPanelsEvent'));
  }

  public function listenToContextLoadFactoriesEvent(sfEvent $event)
  {
    include $event->getSubject()->getConfigCache()->checkConfig('config/fw_lessphp.yml');
    if (sfConfig::get('fw_lessphp_enabled', false))
    {
      $this->fwLessphp = new fwLessphp($event->getSubject()->getResponse(), $this->dispatcher);
    }
  }

  public function listenToResponseMethodNotFoundEvent(sfEvent $event)
  {
    if (sfConfig::get('fw_lessphp_enabled', false))
    {
      if ($event['method'] === 'getLesscssParsedStylesheets')
      {
        $event->setReturnValue(
          call_user_func_array(
            array($this->fwLessphp, 'getLesscssParsedStylesheets'),
            $event['arguments']
          )
        );
        $event->setProcessed(true);
      }
    }
  }

  public function listenToDebugWebLoadPanelsEvent(sfEvent $event)
  {
    $event->getSubject()->setPanel('lessphp', new fwWebDebugPanelLessphp($event->getSubject()));
  }

}
