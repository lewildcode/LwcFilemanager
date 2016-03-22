<?php
namespace LwcFilemanager\View\Helper\Admin;

use Zend\View\Helper\AbstractHelper;

class Icon extends AbstractHelper
{

    /**
     *
     * @param \SplFileInfo $file            
     * @return string
     */
    public function __invoke(\SplFileInfo $file)
    {
        if ($file->isDir()) {
            return '<span class="glyphicon glyphicon-folder-open"></span>';
        }
        if ($this->isImage($file)) {
            return '<span class="glyphicon glyphicon-picture"></span>';
        }
        if($this->isVideo($file)) {
            return '<span class="glyphicon glyphicon-facetime-video"></span>';
        }
        return '<span class="glyphicon glyphicon-file"></span>';
    }

    /**
     *
     * @param \SplFileInfo $file            
     * @return boolean
     */
    protected function isImage(\SplFileInfo $file)
    {
        if ($file->isDir()) {
            return false;
        }
        $ext = strtolower($file->getExtension());
        $images = explode(',', 'jpg,png,jpeg,gif,tiff,psd,bmp');
        return in_array($ext, $images);
    }
    
    /**
     * 
     * @param \SplFileInfo $file
     * @return boolean
     */
    protected function isVideo(\SplFileInfo $file)
    {
        if($file->isDir()) {
            return false;
        }
        $ext = strtolower($file->getExtension());
        $videos = explode(',', 'wmv,ogg,avi,mpg,divx,flv');
        return in_array($ext, $videos);
    }
}