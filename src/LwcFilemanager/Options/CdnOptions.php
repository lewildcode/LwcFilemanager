<?php
namespace LwcFilemanager\Options;

use Zend\Stdlib\AbstractOptions;

class CdnOptions extends AbstractOptions
{

    /**
     *
     * @var string
     */
    protected $hostname;
    
    /**
     *
     * @var string
     */
    protected $basePath;
    
    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }
    
    /**
     * 
     * @param string $basePath
     * @return \LwcFilemanager\Options\CdnOptions
     */
    public function setBasePath($basePath)
    {
        if(!is_dir($basePath)) {
            throw new \InvalidArgumentException('No such path: ' . $basePath);
        }
        $this->basePath = realpath($basePath);
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getCdnHostname()
    {
        return $this->hostname;
    }

    /**
     *
     * @param string $hostname            
     * @return \LwcFilemanager\Options\CdnOptions
     */
    public function setCdnHostname($hostname)
    {
        $this->hostname = (string) $hostname;
        return $this;
    }
}