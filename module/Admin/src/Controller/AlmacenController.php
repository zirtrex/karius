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
use Admin\Model\AlmacenTable;
use Admin\Model\ClienteTable;
use Admin\Model\UsuarioTable;
use Admin\Entity\Almacen;
use Admin\Entity\Cliente;
use Admin\Entity\Usuario;
use Admin\Form\BuscarForm;


class AlmacenController extends AbstractActionController 
{
    private $container;    
    private $almacenTable;
    private $clienteTable;
    private $usuarioTable;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);        
        $this->layout()->setTemplate('layout/admin'); 
        return $response;
    }
    
    public function __construct(ContainerInterface $container,
        AlmacenTable $almacenTable,
        ClienteTable $clienteTable,
        UsuarioTable $usuarioTable)
    {
        $this->container            = $container;
        $this->almacenTable         = $almacenTable;
        $this->clienteTable         = $clienteTable;
        $this->usuarioTable         = $usuarioTable;
    }
    
    public function indexAction()
    {        
        if($this->identity())
        {
            $cod_almacen = (int) $this->params()->fromRoute('cod_almacen', 0);
            $cod_cliente = (int) $this->params()->fromRoute('cod_cliente', 0);
            
            if ($cod_cliente == 0) {
                $this->flashMessenger()->addErrorMessage('¡Debes seleccionar un cliente!');
                return $this->redirect()->toRoute('cliente');
            }            
            
            $almacenes = $this->almacenTable->obtenerPorCodCliente($cod_cliente);
            
            $view = new ViewModel(array(
                'cod_cliente' => $cod_cliente,
                'almacenes' => $almacenes
            ));
            
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
    public function agregarAlmacenAction()
    {
        if($this->identity()){
            
            $cod_almacen = (int) $this->params()->fromRoute('cod_almacen', 0);
            $cod_cliente = (int) $this->params()->fromRoute('cod_cliente', 0);
            
            if ($cod_cliente == 0) {
                $this->flashMessenger()->addErrorMessage('¡Debes seleccionar un cliente!');
                return $this->redirect()->toRoute('cliente');
            }
            
            $form = new \Admin\Form\AlmacenForm();
            $form->get('cod_cliente')->setValue($cod_cliente);
            
            $request = $this->getRequest();
            
            if($request->isPost()){
                $form->setInputFilter(new \Admin\Form\Filter\AlmacenFilter());
                $form->setData($request->getPost());
                
                if ($form->isValid()){
                    $almacen = new Almacen();
                    $almacen->exchangeArray($form->getData());
                    
                    if($this->almacenTable->guardar($almacen)){
                        $this->flashMessenger()->addInfoMessage('¡El almacen: ' . $almacen->nombre_almacen . ', se ha creado correctamente!');
                        return $this->redirect()->toRoute('almacen', ['action' => 'index', 'cod_cliente' => $almacen->cod_cliente]);
                    }                    
                } else {
                    var_dump($form->getMessages());
                }
            } 
            
            $view = new ViewModel([
                'cod_almacen' => $cod_almacen,
                'cod_cliente' => $cod_cliente,
                'form' => $form,
                'text'=>'Agregar',
                'action'=>'agregar-almacen'
            ]);
            $view->setTemplate('admin/almacen/adm-almacen.phtml');
            return $view;            
        }        
        return $this->redirect()->toRoute('ingresar');
        
    }
    
    public function editarAlmacenAction()
    {
        if ($this->identity()) {
            
            $cod_almacen = (int) $this->params()->fromRoute('cod_almacen', 0);
            $cod_cliente = (int) $this->params()->fromRoute('cod_cliente', 0);
            
            if ($cod_almacen == 0 || $cod_cliente == 0) {
                $this->flashMessenger()->addErrorMessage('¡No se ha podido editar!');
                return $this->redirect()->toRoute('cliente');
            }
            
            $form = new \Admin\Form\AlmacenForm();
            
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $form->setInputFilter(new \Admin\Form\Filter\AlmacenFilter());
                
                $form->setData($request->getPost());
                
                if ($form->isValid()){
                    $almacen = new Almacen();
                    $almacen->exchangeArray($form->getData());
                    
                    if($this->almacenTable->guardar($almacen)){
                        $this->flashMessenger()->addSuccessMessage('¡El almacen: ' . $almacen->nombre_almacen. ', se ha editado correctamente!');
                        return $this->redirect()->toRoute('almacen', ['action' => 'index', 'cod_cliente' => $almacen->cod_cliente]);
                    }
                }
            } else {
                  
                $almacen = $this->almacenTable->obtenerPorCodAlmacen($cod_almacen);
                
                if(!$almacen){
                    return $this->redirect()->toRoute('almacen', ['action' => 'index', 'cod_cliente' => $almacen->cod_cliente]);
                }
                $form->populateValues($almacen->getArrayCopy());
            }
            
            $view = new ViewModel([
                'cod_almacen' => $cod_almacen,
                'cod_cliente' => $cod_cliente,
                'form' => $form,
                'text'=>'Editar',
                'action'=>'editar-almacen'
            ]);
            $view->setTemplate('admin/almacen/adm-almacen.phtml');
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
    public function eliminarAlmacenAction()
    {
        if ($this->identity()){
            
            $cod_almacen = (int) $this->params()->fromRoute('cod_almacen', 0);
            $cod_cliente = (int) $this->params()->fromRoute('cod_cliente', 0);
            
            if ($cod_almacen == 0 || $cod_cliente == 0) {
                $this->flashMessenger()->addErrorMessage('¡No se ha podido editar!');
                return $this->redirect()->toRoute('cliente');
            }
            
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $data = $request->getPost();
                
                $respuesta =  $data['eliminar'];
                $cod_almacen = $data['cod_almacen'];
                $cod_cliente = $data['cod_cliente'];
                $nombre_almacen = $data['nombre_almacen'];
                
                if ($respuesta == 'si'){
                    if ($this->almacenTable->eliminar($cod_almacen)) {
                        $this->flashMessenger()->addSuccessMessage('¡El almacen: ' .$nombre_almacen. ', se ha eliminado correctamente!');
                    }
                }else{
                    $this->flashMessenger()->addInfoMessage('¡El almacen: ' . $nombre_almacen. ', no ha sido eliminado!');
                }
                return $this->redirect()->toRoute('almacen', ['action' => 'index', 'cod_cliente' => $cod_cliente]);
            } else {

                $almacen = $this->almacenTable->obtenerPorCodAlmacen($cod_almacen);
                
                if (!$almacen) {
                    return $this->redirect()->toRoute('almacen', ['action' => 'index', 'cod_cliente' => $cod_cliente]);
                }
            }            
            
            $view = new ViewModel([
                'almacen' => $almacen,
                'text'=>'Eliminar',
                'action'=>'eliminar-almacen'
            ]);
            $view->setTemplate('admin/almacen/adm-almacen.phtml');
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
}
