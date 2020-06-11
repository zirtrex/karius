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


class ClienteController extends AbstractActionController 
{
    private $container;    
    private $almacenTable;
    private $clienteTable;
    private $usuarioTable;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);        
        // Set alternative layout
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
            $select = new Select();
            
            $order_by = $this->params()->fromRoute('orderby') ? $this->params()->fromRoute('orderby') : 'cod_cliente';
            
            $order = $this->params()->fromRoute('order') ? $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
            
            $select->order($order_by . ' ' . $order);
            
            $request = $this->getRequest();
            
            $texto = "";
            if ($request->isPost()) {
                $data = $request->getPost();
                $texto = $data['texto'];
                $select->where->like('razon_social', '%' . $texto . '%')
                    ->where->or->like('direccion_legal', '%' . $texto . '%');
            }
            
            $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
            
            $cursos = $this->clienteTable->obtenerTodoPagination($select);
            
            $itemsPerPage = 10;
            
            $cursos->current();
            
            $paginator = new Paginator(new paginatorIterator($cursos));
            
            $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(6);
            
            $buscarForm = new BuscarForm();
            
            $view = new ViewModel(array(
                'clientes' => $paginator,
                //'buscarForm' => new BuscarForm(),
                'orderby' => $order_by,
                'order' => $order
            ));
            $this->layout()->setVariable("buscarForm", $buscarForm);
            $this->layout()->setVariable("route", "cliente");
            $this->layout()->setVariable("texto", $texto);
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
    public function agregarClienteAction()
    {
        if($this->identity()){
            $form = new \Admin\Form\ClienteForm();
            
            $request = $this->getRequest();
            
            if($request->isPost()){
                $form->setInputFilter(new \Admin\Form\Filter\ClienteFilter());
                $form->setData($request->getPost());
                
                if ($form->isValid()){
                    $cliente = new Cliente();
                    $cliente->exchangeArray($form->getData());
                    
                    if($this->clienteTable->guardar($cliente)){
                        $this->flashMessenger()->addInfoMessage('¡El Cliente: ' . $cliente->razon_social . ', se ha creado correctamente!');
                        return $this->redirect()->toRoute('cliente');
                    }                    
                }                
            }
            
            $view = new ViewModel([
                'form' => $form,
                'text'=>'Agregar',
                'action'=>'agregar-cliente'
            ]);
            $view->setTemplate('admin/cliente/adm-cliente.phtml');
            return $view;            
        }        
        return $this->redirect()->toRoute('ingresar');
        
    }
    
    public function editarClienteAction()
    {
        if($this->identity())
        {
            $form = new \Admin\Form\ClienteForm();
            
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $form->setInputFilter(new \Admin\Form\Filter\ClienteFilter());
                
                $form->setData($request->getPost());
                
                if ($form->isValid()){
                    $cliente = new Cliente();
                    $cliente->exchangeArray($form->getData());
                    
                    if($this->clienteTable->guardar($cliente)){
                        $this->flashMessenger()->addInfoMessage('¡El Cliente: ' . $cliente->razon_social . ', se ha editado correctamente!');
                        return $this->redirect()->toRoute('cliente');
                    }
                }
            } else {
                $cod_cliente = (int) $this->params()->fromRoute('cod_cliente', 0);
                
                if(!$cod_cliente){
                    return $this->redirect()->toRoute('cliente');
                }
                    
                $clienteSeleccionado = $this->clienteTable->obtenerCliente($cod_cliente);
                
                if(!$clienteSeleccionado){
                    return $this->redirect()->toRoute('cliente');
                }
                $form->populateValues($clienteSeleccionado->getArrayCopy());
            }
            
            $view = new ViewModel([
                'form' => $form,
                'text'=>'Editar',
                'action'=>'editar-cliente'
            ]);
            $view->setTemplate('admin/cliente/adm-cliente.phtml');
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
    public function eliminarClienteAction()
    {
        if ($this->identity())
        {
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                $data = $request->getPost();
                
                $respuesta =  $data['eliminar'];
                $cod_cliente = $data['cod_cliente'];
                $razon_social = $data['razon_social'];
                
                if ($respuesta == 'si'){
                    if ($this->clienteTable->eliminar($cod_cliente)) {
                        $this->flashMessenger()->addInfoMessage('¡El Cliente: ' . $razon_social . ', se ha eliminado correctamente!');
                    }
                }else{
                    $this->flashMessenger()->addInfoMessage('¡El Cliente: ' . $razon_social . ', no ha sido eliminado!');
                }
                return $this->redirect()->toRoute('cliente');
            } else {
                $cod_cliente = (int) $this->params()->fromRoute('cod_cliente', 0);
                
                if (!$cod_cliente){
                    return $this->redirect()->toRoute('cliente');
                }
            }
            
            $cliente = $this->clienteTable->obtenerCliente($cod_cliente);
            
            $view = new ViewModel([
                'Cliente' => $cliente,
                'text'=>'Eliminar',
                'action'=>'eliminar-cliente'
            ]);
            $view->setTemplate('admin/cliente/adm-cliente.phtml');
            return $view;
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
}
