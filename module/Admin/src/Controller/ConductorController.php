<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;
use Interop\Container\ContainerInterface;
use Laminas\Db\Sql\Select;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\Iterator as paginatorIterator;
use Admin\Model\Miscellanea;
use Admin\Model\ConductorTable;
use Admin\Entity\Conductor;
use Admin\Form\BuscarForm;


class ConductorController extends AbstractActionController 
{
    private $container;    
    private $conductorTable;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);        
        $this->layout()->setTemplate('layout/admin'); 
        return $response;
    }
    
    public function __construct(ContainerInterface $container, ConductorTable $conductorTable)
    {
        $this->container            = $container;
        $this->conductorTable       = $conductorTable;
    }
    
    public function indexAction()
    {        
        if($this->identity())
        {
            $select = new Select();
            
            $order_by = $this->params()->fromRoute('orderby') ? $this->params()->fromRoute('orderby') : 'cod_conductor';
            
            $order = $this->params()->fromRoute('order') ? $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
            
            $select->order($order_by . ' ' . $order);
            
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $data = $request->getPost();
                $texto = $data['texto'];
                $select->where->like('nombres', '%' . $texto . '%')
                    ->where->or->like('apellidos', '%' . $texto . '%')
                    ->where->or->like('numero_licencia', '%' . $texto . '%');
            }
            
            $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
            
            $cursos = $this->conductorTable->obtenerTodoPagination($select);
            
            $itemsPerPage = 10;
            
            $cursos->current();
            
            $paginator = new Paginator(new paginatorIterator($cursos));
            
            $paginator->setCurrentPageNumber($page)
            ->setItemCountPerPage($itemsPerPage)
            ->setPageRange(6);
            
            $buscarForm = new BuscarForm();            
            
            $view = new ViewModel(array(
                'conductores' => $paginator,
                'orderby' => $order_by,
                'order' => $order
            ));
            $this->layout()->setVariable("buscarForm", $buscarForm);
            $this->layout()->setVariable("route", "conductor");
            $this->layout()->setVariable("texto", $texto);
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
    public function agregarConductorAction()
    {
        if($this->identity()){
            $form = new \Admin\Form\ConductorForm();
            
            $request = $this->getRequest();
            
            if($request->isPost()){
                $form->setInputFilter(new \Admin\Form\Filter\ConductorFilter());
                $form->setData($request->getPost());
                
                if ($form->isValid()){
                    $conductor = new Conductor();
                    $conductor->exchangeArray($form->getData());
                    
                    if($this->conductorTable->guardar($conductor)){
                        $this->flashMessenger()->addInfoMessage('¡El Conductor: ' . $conductor->nombres . ', se ha creado correctamente!');
                        return $this->redirect()->toRoute('conductor');
                    }                    
                }                
            }
            
            $view = new ViewModel([
                'form' => $form,
                'text'=>'Agregar',
                'action'=>'agregar-conductor'
            ]);
            $view->setTemplate('admin/conductor/adm-conductor.phtml');
            return $view;            
        }        
        return $this->redirect()->toRoute('ingresar');
        
    }
    
    public function editarConductorAction()
    {
        if($this->identity())
        {
            $form = new \Admin\Form\ConductorForm();
            
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $form->setInputFilter(new \Admin\Form\Filter\ConductorFilter());
                
                $form->setData($request->getPost());
                
                if ($form->isValid()){
                    $conductor = new Conductor();
                    $conductor->exchangeArray($form->getData());
                    
                    if($this->conductorTable->guardar($conductor)){
                        $this->flashMessenger()->addInfoMessage('¡El Conductor: ' . $conductor->nombres . ', se ha editado correctamente!');
                        return $this->redirect()->toRoute('conductor');
                    }
                }
            } else {
                $cod_conductor = (int) $this->params()->fromRoute('cod_conductor', 0);
                
                if(!$cod_conductor){
                    return $this->redirect()->toRoute('conductor');
                }
                    
                $conductor = $this->conductorTable->obtenerConductor($cod_conductor);
                
                if(!$conductor){
                    return $this->redirect()->toRoute('$conductor');
                }
                $form->populateValues($conductor->getArrayCopy());
            }
            
            $view = new ViewModel([
                'form' => $form,
                'text'=>'Editar',
                'action'=>'editar-conductor'
            ]);
            $view->setTemplate('admin/conductor/adm-conductor.phtml');
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
    public function eliminarConductorAction()
    {
        if ($this->identity())
        {
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $data = $request->getPost();
                
                $respuesta =  $data['eliminar'];
                $cod_conductor = $data['cod_conductor'];
                $nombres = $data['nombres'];
                
                if ($respuesta == 'si'){
                    if ($this->conductorTable->eliminar($cod_conductor)) {
                        $this->flashMessenger()->addInfoMessage('¡El Conductor: ' . $nombres . ', se ha eliminado correctamente!');
                    }
                }else{
                    $this->flashMessenger()->addInfoMessage('¡El Conductor: ' . $nombres . ', no ha sido eliminado!');
                }
                return $this->redirect()->toRoute('conductor');
            } else {
                $cod_conductor = (int) $this->params()->fromRoute('cod_conductor', 0);
                
                if (!$cod_conductor){
                    return $this->redirect()->toRoute('conductor');
                }
            }
            
            $conductor = $this->conductorTable->obtenerConductor($cod_conductor);
            
            $view = new ViewModel([
                'Conductor' => $conductor,
                'text'=>'Eliminar',
                'action'=>'eliminar-conductor'
            ]);
            $view->setTemplate('admin/conductor/adm-conductor.phtml');
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
}
