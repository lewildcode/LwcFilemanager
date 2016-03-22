<?php
namespace LwcFilemanager;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\EventManager\EventInterface;
use Zend\Config\Exception\InvalidArgumentException;
use Zend\Mvc\MvcEvent;

class Module implements ConfigProviderInterface, AutoloaderProviderInterface, BootstrapListenerInterface
{

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ModuleManager\Feature\ConfigProviderInterface::getConfig()
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * (non-PHPdoc)
     * @see \Zend\ModuleManager\Feature\BootstrapListenerInterface::onBootstrap()
     */
    public function onBootstrap(EventInterface $e)
    {
        $config = $this->getConfig();
        $basePath = $config['lwcfilemanager']['base_path'];
        
        if (!is_dir($basePath)) {
            throw new InvalidArgumentException('File manager path does not exist.');
        }
        if(!is_writable($basePath)) {
            throw new InvalidArgumentException('File manager path is read-only.');
        }
        
        $em = $e->getApplication()->getEventManager();
        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onRender'));
    }
    
    /**
     * 
     * @param MvcEvent $e
     */
    public function onRender(MvcEvent $e)
    {
        if(!strstr($e->getRouteMatch()->getMatchedRouteName(), 'zfcadmin')) {
            return;
        }
        $sm = $e->getApplication()->getServiceManager();
        $view = $e->getViewModel();
        $renderer = $sm->get('Zend\View\Renderer\PhpRenderer');
        
        $jsFile = 'js/jquery/lwcfilemanager.picker.js';
        $renderer->headScript()->appendFile($renderer->basePath($jsFile));
        
        $jsData = array(
            'lwcfilemanager' => array(
                'cdnUrl' => $renderer->cdnUrl(''),
                'pickerUrl' => $renderer->url('zfcadmin/lwcfilemanager_ajax', array(
                    'action' => 'picker'
                ))
            )
        );
        $encoded = json_encode($jsData);
        $renderer->inlineScript()->appendScript("var mwAppSettings = " . $encoded . ";");
    }
}