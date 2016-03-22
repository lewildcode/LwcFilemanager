<?php
namespace LwcFilemanager\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FilemanagerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $path = $config['lwcfilemanager']['base_path'];
        return new Filemanager($path);
    }
}