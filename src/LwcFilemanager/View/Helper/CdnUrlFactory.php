<?php
namespace LwcFilemanager\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use LwcFilemanager\Options\CdnOptions;

class CdnUrlFactory implements FactoryInterface
{

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\ServiceManager\FactoryInterface::createService()
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->getServiceLocator()->get('config');
        $options = new CdnOptions($config['lwcfilemanager']);
        return new CdnUrl($options);
    }
}