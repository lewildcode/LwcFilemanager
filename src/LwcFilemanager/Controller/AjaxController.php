<?php
namespace LwcFilemanager\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AjaxController extends AbstractActionController
{

    public function pickerAction()
    {
        $view = new ViewModel();
        return $view->setTerminal(true);
    }
}