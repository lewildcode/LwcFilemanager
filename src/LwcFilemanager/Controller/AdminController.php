<?php
namespace LwcFilemanager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use LwcFilemanager\Service\Filemanager;
use Zend\View\Model\ViewModel;
use LwcFilemanager\Form\UploadFiles;

class AdminController extends AbstractActionController
{

    /**
     *
     * @var Filemanager
     */
    protected $filemanager;

    /**
     *
     * @param Filemanager $service            
     */
    public function __construct(Filemanager $service)
    {
        $this->filemanager = $service;
    }

    public function indexAction()
    {
        $base = $this->params()->fromQuery('base');
        $baseDecoded = base64_decode($base);
        
        $it = $this->filemanager->getFileList($baseDecoded);
        
        $uploadForm = new UploadFiles();
        $uploadForm->get('base')->setValue($base);
        $isAjax = $this->getRequest()->isXmlHttpRequest();
        $view = new ViewModel();
        $view->setVariables(array(
            'dir' => $it,
            'baseDecoded' => $baseDecoded,
            'base' => $base,
            'isAjax' => $isAjax,
            'uploadForm' => $uploadForm
        ));
        return $view->setTerminal($isAjax);
    }

    public function uploadAction()
    {
        if (! $this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('zfcadmin/lwcfilemanager');
        }
        
        $form = new UploadFiles();
        $request = $this->getRequest();
        $form->setData(array_merge_recursive($request->getPost()
            ->toArray(), $request->getFiles()
            ->toArray()));
        $base = $form->get('base')->getValue();
        
        // where to put them
        $targetPath = null;
        if (! empty($base)) {
            $targetPath = base64_decode($base);
        }
        $files = $form->get('files')->getValue();
        $overwriteFlag = $form->get('overwrite')->getValue();
        foreach ($files as $i => $file) {
            $this->filemanager->createFile($file, $targetPath, $overwriteFlag);
        }
        return $this->redirect()->toRoute('zfcadmin/lwcfilemanager', array(), array(
            'query' => array(
                'base' => $base
            )
        ));
    }

    public function deleteFolderAction()
    {
        $base = $this->params()->fromQuery('base');
        if (! empty($base)) {
            $this->filemanager->deleteSubfolder(base64_decode($base));
        }
        return $this->redirect()->toRoute('zfcadmin/lwcfilemanager');
    }

    public function deleteFileAction()
    {
        $base = $this->params()->fromQuery('base');
        $dirname = null;
        if (! empty($base)) {
            if ($this->filemanager->deleteFile(base64_decode($base))) {
                $dirname = dirname($base);
            }
        }
        $base = null;
        return $this->redirect()->toRoute('zfcadmin/lwcfilemanager', array(), array(
            'query' => array(
                'base' => $dirname
            )
        ));
    }

    public function newFolderAction()
    {
        if (! $this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('zfcadmin/lwcfilemanager');
        }
        $name = $this->params()->fromPost('folder-name');
        $base = $this->params()->fromPost('base', null);
        $target = $this->filemanager->createSubfolder($name, base64_decode($base));
        if($target) {
            return $this->redirect()->toRoute('zfcadmin/lwcfilemanager', array(), array(
                'query' => array(
                    'base' => base64_encode($target)
                )
            ));
        }
        return $this->redirect()->toRoute('zfcadmin/lwcfilemanager');
    }
}