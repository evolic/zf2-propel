<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function changeAction()
    {
        $lang = $this->params('lang');
        if (!empty($lang)) {
            $session = $this->getServiceLocator()->get('session');
            $session->lang = $lang;
        }    
        return $this->getResponse();

    }
}
