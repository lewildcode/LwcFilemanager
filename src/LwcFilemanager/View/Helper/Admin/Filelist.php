<?php
namespace LwcFilemanager\View\Helper\Admin;

use Zend\View\Helper\AbstractHelper;

class Filelist extends AbstractHelper
{

    /**
     *
     * @param array $dir            
     * @param boolean $ajax            
     * @return string html
     */
    public function __invoke(array $dir, $ajax)
    {
        return $this->getView()->render($this->getViewModel(), array(
            'dir' => $dir,
            'ajax' => $ajax
        ));
    }

    /**
     *
     * @return string
     */
    protected function getViewModel()
    {
        return 'lwcfilemanager/filelist';
    }
}