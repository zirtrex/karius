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
use Admin\Model\VehiculoTable;
use Admin\Entity\Vehiculo;
use Admin\Form\BuscarForm;


class VehiculoController extends AbstractActionController 
{
    private $container;    
    private $vehiculoTable;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);        
        $this->layout()->setTemplate('layout/admin'); 
        return $response;
    }
    
    public function __construct(ContainerInterface $container, VehiculoTable $vehiculoTable)
    {
        $this->container        = $container;
        $this->vehiculoTable    = $vehiculoTable;
    }
    
    public function indexAction()
    {        
        if ($this->identity()) {
            
            $select = new Select();
            
            $order_by = $this->params()->fromRoute('orderby') ? $this->params()->fromRoute('orderby') : 'cod_vehiculo';
            
            $order = $this->params()->fromRoute('order') ? $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
            
            $select->order($order_by . ' ' . $order);
            
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $data = $request->getPost();
                $texto = $data['texto'];
                $select->where->like('marca', '%' . $texto . '%')
                    ->where->or->like('placa', '%' . $texto . '%')
                    ->where->or->like('modelo', '%' . $texto . '%');
            }
            
            $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
            
            $vehiculos = $this->vehiculoTable->obtenerTodoPagination($select);
            
            $itemsPerPage = 10;
            
            $vehiculos->current();
            
            $paginator = new Paginator(new paginatorIterator($vehiculos));
            
            $paginator->setCurrentPageNumber($page)
            ->setItemCountPerPage($itemsPerPage)
            ->setPageRange(6);
            
            $buscarForm = new BuscarForm();            
            
            $view = new ViewModel(array(
                'vehiculos' => $paginator,
                'orderby' => $order_by,
                'order' => $order
            ));
            $this->layout()->setVariable("buscarForm", $buscarForm);
            $this->layout()->setVariable("route", "vehiculo");
            $this->layout()->setVariable("texto", $texto);
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
    public function agregarVehiculoAction()
    {
        if($this->identity()){
            $form = new \Admin\Form\VehiculoForm();
            
            $request = $this->getRequest();
            
            if($request->isPost()){
                $form->setInputFilter(new \Admin\Form\Filter\VehiculoFilter());
                $form->setData($request->getPost());
                
                if ($form->isValid()){
                    $vehiculo = new Vehiculo();
                    $vehiculo->exchangeArray($form->getData());
                    
                    if($this->vehiculoTable->guardar($vehiculo)){
                        $this->flashMessenger()->addInfoMessage('¡El Vehiculo: ' . $vehiculo->placa . ', se ha creado correctamente!');
                        return $this->redirect()->toRoute('vehiculo');
                    }                    
                }                
            }
            
            $view = new ViewModel([
                'form' => $form,
                'text'=>'Agregar',
                'action'=>'agregar-vehiculo'
            ]);
            $view->setTemplate('admin/vehiculo/adm-vehiculo.phtml');
            return $view;            
        }        
        return $this->redirect()->toRoute('ingresar');
        
    }
    
    public function editarVehiculoAction()
    {
        if($this->identity())
        {
            $form = new \Admin\Form\VehiculoForm();
            
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $form->setInputFilter(new \Admin\Form\Filter\VehiculoFilter());
                
                $form->setData($request->getPost());
                
                if ($form->isValid()){
                    $vehiculo = new Vehiculo();
                    $vehiculo->exchangeArray($form->getData());
                    
                    if($this->vehiculoTable->guardar($vehiculo)){
                        $this->flashMessenger()->addInfoMessage('¡El vehiculo: ' . $vehiculo->placa . ', se ha editado correctamente!');
                        return $this->redirect()->toRoute('vehiculo');
                    }
                }
            } else {
                $cod_vehiculo= (int) $this->params()->fromRoute('cod_vehiculo', 0);
                
                if(!$cod_vehiculo){
                    return $this->redirect()->toRoute('vehiculo');
                }
                    
                $vehiculo = $this->vehiculoTable->obtenerVehiculo($cod_vehiculo);
                
                if(!$vehiculo){
                    return $this->redirect()->toRoute('$vehiculo');
                }
                $form->populateValues($vehiculo->getArrayCopy());
            }
            
            $view = new ViewModel([
                'form' => $form,
                'text'=>'Editar',
                'action'=>'editar-vehiculo'
            ]);
            $view->setTemplate('admin/vehiculo/adm-vehiculo.phtml');
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
    public function eliminarVehiculoAction()
    {
        if ($this->identity())
        {
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $data = $request->getPost();
                
                $respuesta =  $data['eliminar'];
                $cod_vehiculo = $data['cod_vehiculo'];
                $placa = $data['placa'];
                
                if ($respuesta == 'si'){
                    if ($this->vehiculoTable->eliminar($cod_vehiculo)) {
                        $this->flashMessenger()->addInfoMessage('¡El vehiculo: ' . $placa. ', se ha eliminado correctamente!');
                    }
                }else{
                    $this->flashMessenger()->addInfoMessage('¡El vehiculo: ' . $placa. ', no ha sido eliminado!');
                }
                return $this->redirect()->toRoute('vehiculo');
            } else {
                $cod_vehiculo = (int) $this->params()->fromRoute('cod_vehiculo', 0);
                
                if (!$cod_vehiculo) {
                    return $this->redirect()->toRoute('vehiculo');
                }
            }
            
            $vehiculo = $this->vehiculoTable->obtenerVehiculo($cod_vehiculo);
            
            $view = new ViewModel([
                'vehiculo' => $vehiculo,
                'text'=>'Eliminar',
                'action'=>'eliminar-vehiculo'
            ]);
            $view->setTemplate('admin/vehiculo/adm-vehiculo.phtml');
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
}
