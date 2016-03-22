<?php
namespace LwcFilemanager\Service;

use Zend\Filter\File\RenameUpload;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;

class Filemanager implements EventManagerAwareInterface
{

    /**
     *
     * @var EventManagerInterface
     */
    protected $eventManager;

    /**
     *
     * @var string
     */
    protected $basepath;

    /**
     *
     * @param string $basepath            
     */
    public function __construct($basepath)
    {
        $this->setBasepath($basepath);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\EventManager\EventsCapableInterface::getEventManager()
     */
    public function getEventManager()
    {
        if (null === $this->eventManager) {
            $this->setEventManager(new EventManager());
        }
        
        return $this->eventManager;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Zend\EventManager\EventManagerAwareInterface::setEventManager()
     */
    public function setEventManager(EventManagerInterface $eventManager)
    {
        $eventManager->addIdentifiers(array(
            get_called_class()
        ));
        $this->eventManager = $eventManager;
        return $this;
    }

    /**
     *
     * @param string $path            
     * @throws \InvalidArgumentException
     * @return \LwcFilemanager\Service\Filemanager
     */
    public function setBasepath($basepath)
    {
        if (! is_dir($basepath)) {
            throw new \InvalidArgumentException('No such dir: ' . $basepath);
        }
        if (! is_readable($basepath)) {
            throw new \InvalidArgumentException('Not readable: ' . $basepath);
        }
        $this->basepath = (string) $basepath;
        return $this;
    }

    /**
     *
     * @param string $name            
     * @param string $parentPath
     *            OPTIONAL relative path
     * @return boolean|string
     */
    public function createSubfolder($name, $parentPath = null)
    {
        if (empty($parentPath) || !$this->isCdnPath($parentPath)) {
            $parentPath = $this->getBasepath();
        }
        if (strstr($name, DIRECTORY_SEPARATOR)) {
            return false;
        }
        $target = $parentPath . DIRECTORY_SEPARATOR . $name;
        if (file_exists($target) || !mkdir($target, 0777)) {
            return false;
        }
        return $target;
    }
    
    /**
     * 
     * @param string $realpath
     * @return boolean
     */
    protected function isCdnPath($realpath)
    {
        if(empty($realpath)) {
            return false;
        }
        $search = $this->getBasepath() . DIRECTORY_SEPARATOR;
        $stripCdn = str_replace($search, '', $realpath);
        
        return !empty($stripCdn);
    }

    /**
     * @param string $realpath
     * @param bool $recursive
     * @return bool
     */
    public function deleteSubfolder($realpath, $recursive = true)
    {
        if(!$this->isCdnPath($realpath)) {
            return false;
        }
        try {
            $it = new \DirectoryIterator($realpath);
        } catch(\UnexpectedValueException $e) {
            return false;
        }

        foreach ($it as $file) {
            if($file->isDot()) {
                continue;
            }
            if ($file->isFile() && $recursive) {
                $this->deleteFile($file->getRealPath());
            } else if($file->isDir() && $realpath !== $file->getRealPath() && $recursive) {
                $this->deleteSubfolder($file->getRealPath(), $recursive);
            }
        }
        return rmdir($it->getPath());
    }

    /**
     *
     * @param string $file            
     * @return boolean
     */
    public function deleteFile($file)
    {
        if (! is_file($file) || ! is_writable($file)) {
            return false;
        }
        $dirname = dirname($file);
        if (! strstr($dirname, $this->getBasepath())) {
            return false;
        }
        if (unlink($file)) {
            $relPathSearch = $this->getBasepath() . DIRECTORY_SEPARATOR;
            $this->getEventManager()->trigger('deleteFile', null, array(
                'relative_path' => str_replace($relPathSearch, '', $file)
            ));
            return true;
        }
        return false;
    }

    /**
     *
     * @param array $fileInfo            
     * @param string $targetPath
     *            OPTIONAL target directory (realpath)
     * @param boolean $overwrite
     *            OPTIONAL whether or not to replace existing
     * @return array
     */
    public function createFile(array $fileInfo, $targetPath = null, $overwrite = false)
    {
        $it = $this->getStorage();
        
        if (empty($targetPath) || ! is_dir($targetPath)) {
            $targetPath = $it->getRealPath();
        }
        
        $filter = new RenameUpload($targetPath);
        $filter->setOverwrite($overwrite);
        $filter->setUseUploadName(true);
        return $filter->filter($fileInfo);
    }
    
    /**
     * 
     * @param string $src
     * @param string $target
     * @return boolean
     */
    public function renameFile($src, $target)
    {
        return rename($src, $target);
    }

    /**
     *
     * @param string $path
     *            OPTIONAL relative path to add to the cdn realpath
     * @return string realpath
     */
    public function getBasepath($path = null)
    {
        $realpath = realpath($this->basepath);
        if (empty($path)) {
            return $realpath;
        }
        $result = $realpath . DIRECTORY_SEPARATOR . $path;
        if(is_dir($result)) {
            return $result;
        }
        return false;
    }

    /**
     *
     * @return \DirectoryIterator
     */
    public function getStorage()
    {
        return new \DirectoryIterator($this->getBasepath());
    }

    /**
     *
     * @param string $base
     *            OPTIONAL
     * @return array
     */
    public function getFileList($startingPoint = null)
    {
        $cdn = $this->getBasepath();
        if (empty($startingPoint)) {
            $storage = $this->getStorage();
        } else {
            $storage = new \DirectoryIterator($startingPoint);
        }
        $list = array();
        foreach ($storage as $object) {
            if($object->isDot()) {
                continue;
            }
            /* @var $object \SplFileInfo */
            $entry = str_replace($cdn, '', $object->getRealPath());
            if (substr($entry, 0, 1) == '/') {
                $entry = substr($entry, 1);
            }
            $list[$entry] = $object->getFileInfo();
        }
        uasort($list, function($a, $b) {
            return strcmp($a->getFilename(), $b->getFilename());
        });
        return $list;
    }
}