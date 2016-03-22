<?php
namespace LwcFilemanager\View\Helper;

use Zend\View\Helper\AbstractHelper;
use LwcFilemanager\Options\CdnOptions;

class CdnUrl extends AbstractHelper
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
     * @param string $path            
     * @return string
     */
    public function __invoke($path)
    {
        return '//' . $this->getHostname() . $this->view->basePath($path);
    }

    /**
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->options->getCdnHostname();
    }
}