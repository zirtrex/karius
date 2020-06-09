<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\MvcEvent;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController 
{ 
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        // Set alternative layout
        $this->layout()->setTemplate('layout/login');
        return $response;
    }
    
    public function __construct(){}
    
    public function indexAction()
    {        
        return new ViewModel([]);
    }   
    
}
