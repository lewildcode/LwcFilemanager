<?php
namespace LwcFilemanager\Service;

interface FilemanagerAwareInterface
{

    /**
     *
     * @param Filemanager $service            
     * @return FilemanangerAwareInterface
     */
    public function setFilemanager(Filemanager $service);
}