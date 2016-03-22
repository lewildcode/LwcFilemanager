<?php
namespace LwcFilemanager\View\Helper;

use Zend\View\Helper\AbstractHelper;
use LwcFilemanager\Options\CdnOptions;

class BackLink extends AbstractHelper
{

    /**
     *
     * @var CdnOptions
     */
    protected $options;

    /**
     *
     * @param CdnOptions $options            
     */
    public function __construct(CdnOptions $options)
    {
        $this->options = $options;
    }

    /**
     *
     * @param string $currentPath            
     * @return string
     */
    public function __invoke($currentPath)
    {
        $decoded = base64_decode($currentPath);
        $parts = explode(DIRECTORY_SEPARATOR, $decoded);
        array_pop($parts);
        $cdn = $this->options->getBasePath();
        if(!$this->isPartOfCdn(join(DIRECTORY_SEPARATOR, $parts))) {
            return false;
        }
        
        $url = $this->getView()->url('zfcadmin/lwcfilemanager');
        $url .= '?base=' . base64_encode(join(DIRECTORY_SEPARATOR, $parts));
        return $url;
    }
    
    /**
     * 
     * @param string $realpath
     * @return boolean
     */
    protected function isPartOfCdn($realpath)
    {
        $cdn = $this->options->getBasePath();
        return strstr($realpath, $cdn);
    }
}