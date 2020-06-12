<?php

namespace Traslado\Controller;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Db\Sql\Select;
use Laminas\Paginator\Paginator;
use Laminas\Paginator\Adapter\Iterator as paginatorIterator;
use Admin\Model\Miscellanea;
use Admin\Model\AlmacenTable;
use Admin\Model\ClienteTable;
use Admin\Model\ConductorTable;
use Admin\Model\DestinatarioTable;
use Admin\Model\TrasladoTable;
use Admin\Model\UsuarioTable;
use Admin\Model\VehiculoTable;
use Admin\Entity\Traslado;
use Admin\Entity\Cliente;
use Admin\Entity\Vehiculo;
use Admin\Entity\Conductor;
use Admin\Entity\Destinatario;
use Admin\Entity\Usuario;


class TrasladoController extends AbstractActionController 
{    
    private $container;
    private $almacenTable;
    private $clienteTable;
    private $conductorTable;
    private $destinatarioTable;
    private $trasladoTable;
    private $usuarioTable;
    private $vehiculoTable;    
    
    public function __construct(ContainerInterface $container,
        AlmacenTable $almacenTable,
        ClienteTable $clienteTable,
        ConductorTable $conductorTable,
        DestinatarioTable $destinatarioTable,
        TrasladoTable $trasladoTable,
        UsuarioTable $usuarioTable,
        VehiculoTable $vehiculoTable)
    {
        $this->container            = $container;
        $this->almacenTable         = $almacenTable;
        $this->clienteTable         = $clienteTable;
        $this->conductorTable       = $conductorTable;
        $this->destinatarioTable    = $destinatarioTable;
        $this->trasladoTable        = $trasladoTable;
        $this->usuarioTable         = $usuarioTable;
        $this->vehiculoTable        = $vehiculoTable;
    }
    
    public function indexAction()
    {
        return new ViewModel([]);
    } 
    
    public function verTrasladosAction()
    {
        if($this->identity())
        {
            $select = new Select();
            
            $select->where(['cod_usuario' => (int) $this->getDatosUsuario()->cod_usuario]);
            
            $order_by = $this->params()->fromRoute('orderby') ? $this->params()->fromRoute('orderby') : 'cod_traslado';
            
            $order = $this->params()->fromRoute('order') ? $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
            
            $select->order($order_by . ' ' . $order);
            
            $request = $this->getRequest();
            
            /*if($request->isPost())
            {
                $data = $request->getPost();
                $texto = $data['texto'];
                $select->where->like('nombres','%'.$texto.'%');
            }*/
            
            $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
            
            $traslados = $this->trasladoTable->obtenerTodoPagination($select);
            
            $itemsPerPage = 20;
            
            $traslados->current();
            
            $paginator = new Paginator(new paginatorIterator($traslados));
            
            $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(6);
            
            return new ViewModel(array(
                'traslados' => $paginator,
                'orderby' => $order_by,
                'order' => $order,
            ));
        }
        
        return $this->redirect()->toRoute('ingresar');
        
    }
	
	public function verTraslados2Action()
	{
		if($this->identity())
		{
			$cod_usuario = $this->getDatosUsuario()->cod_usuario;			
            
			$traslados = $this->trasladoTable->obtenerTodo(false);
            
            return new ViewModel([
                'traslados' => $traslados
            ]);
        }
        
        return $this->redirect()->toRoute('ingresar');
	}
	
	public function nuevoTrasladoAction()
	{
	    if($this->identity())
	    {
	        $usuario   = $this->getDatosUsuario();
	        $cod_traslado = (int) $this->params()->fromRoute('cod_traslado', 0);
	        
	        if($cod_traslado != 0){	        
	           $traslado = $this->trasladoTable->obtenerTraslado($cod_traslado);
	        }else{
	            $traslado = new Traslado();
	        }
	        
	        //var_dump($traslado);
	        
            return new ViewModel([
                'title' => ($cod_traslado == 0) ? "Nuevo Traslado" : "Editar Traslado",
                'cod_traslado' => $cod_traslado,
                'traslado' => \Laminas\Json\Json::encode($traslado)
            ]);
	    }
	    
	    return $this->redirect()->toRoute('ingresar');
	}

	public function agregarDestinatariosAction()
	{
	    if($this->identity())
	    {
	        $usuario   = $this->getDatosUsuario();
	        $cod_traslado = (int) $this->params()->fromRoute('cod_traslado', '');
	        
	        if($cod_traslado == 0){
	            return $this->redirect()->toRoute('traslado', ['action' => 'nuevo-traslado']);
	        }
	        
	        $traslado = $this->trasladoTable->obtenerTraslado($cod_traslado);
	        
	        //var_dump($traslado);
	        
	        return new ViewModel([
	            'cod_traslado' => $cod_traslado,
	            'traslado' => \Laminas\Json\Json::encode($traslado)
	        ]);
	    }
	    
	    return $this->redirect()->toRoute('ingresar');
	}
	
	public function guardarTrasladoAjaxAction()
	{	
		if ($this->identity()) {
		    
		    $request = $this->getRequest();
		    $response = $this->getResponse();
		    
		    if ($request->isPost())
		    {
		        $data = $request->getPost();
		        $errorMessage = "";
		        //$response->setContent(\Laminas\Json\Json::encode($data));
		        
		        $traslado = new Traslado();	
		        $traslado->cod_traslado           = $data['cod_traslado'];
		        $traslado->fecha_traslado         = $data['fecha_traslado'];
		        $traslado->punto_partida          = $data['punto_partida'];
		        $traslado->punto_llegada          = $data['punto_llegada'];
		        $traslado->hora_llegada           = $data['hora_llegada'];
		        $traslado->temperatura_llegada    = $data['temperatura_llegada'];
		        $traslado->humedad_relativa_llegada   = $data['humedad_relativa_llegada'];
		        $traslado->hora_salida            = $data['hora_salida'];
		        $traslado->temperatura_salida     = $data['temperatura_salida'];
	            $traslado->humedad_relativa_salida     = $data['humedad_relativa_salida'];
		        $traslado->total                  = $data['total'];
		                
		        $traslado->Usuario = new Usuario();
		        $traslado->Usuario->cod_usuario = $this->getDatosUsuario()->cod_usuario;
		        
		        $traslado->Cliente = new Cliente();
		        $traslado->Cliente->cod_cliente = $data['cod_cliente'];
		        
		        $traslado->Vehiculo = new Vehiculo();
		        $traslado->Vehiculo->cod_vehiculo = $data['cod_vehiculo'];
		        
		        $traslado->Conductor = new Conductor();
		        $traslado->Conductor->cod_conductor = $data['cod_conductor'];		                
		                
                try{
                    $cod_traslado = $this->trasladoTable->guardar($traslado);
                }
                catch (\Exception $e){                                      
                    $errorCodeMessage = $e->getCode();
                    
                    if($errorCodeMessage== "23000"){
                        $errorMessage= " Verifique que los datos no hayan sido registrados antes o que los elementos no esten duplicados.";
                        //$errorMessage= $e->getMessage();
                    }else{
                        $errorMessage= $e->getMessage();
                    }
                }
                
                if ($errorMessage== ""){
                    $response->setContent(\Laminas\Json\Json::encode([
                        'response' => true,
                        'successMessage' => "El traslado ha sido guardado correctamente.",
                        'cod_traslado' => $cod_traslado
                    ]));
                } else {
                    $response->setContent(\Laminas\Json\Json::encode([
                        'response' => false,
                        'errorCode' => $errorCodeMessage,
                        'errorMessage' => $errorMessage,
                        'cod_traslado' => 0
                    ]));
                }
		    }
		    return $response;
		}
	
	}
	
	public function obtenerClientesAjaxAction()
	{
	    if($this->identity())
	    {
	        $clientes = $this->clienteTable->obtenerTodo();	        
	        return $this->getResponse()->setContent(\Laminas\Json\Json::encode($clientes));
	    }	    
	}
	
	public function obtenerAlmacenesAjaxAction()
	{
	    if($this->identity())
	    {
	        $request = $this->getRequest();
	        $response = $this->getResponse();
	        
	        if ($request->isPost())
	        {
	            $data = $request->getPost();
	            
	            $cod_cliente =  $data['cod_cliente'];
	            
	            $almacenes = $this->almacenTable->obtenerPorCodCliente($cod_cliente);
	            
	            $response->setContent(\Laminas\Json\Json::encode($almacenes));
	        }
	        
	        return $response;
	    }
	}
	
	public function obtenerConductoresAjaxAction()
	{
	    if($this->identity())
	    {	        
	        $conductores = $this->conductorTable->obtenerTodo(false);
	        return $this->getResponse()->setContent(\Laminas\Json\Json::encode($conductores));
	    }	    
	}
	
	public function obtenerVehiculosAjaxAction()
	{
	    if($this->identity())
	    {
	        $vehiculos = $this->vehiculoTable->obtenerTodo(false);	        
	        return $this->getResponse()->setContent(\Laminas\Json\Json::encode($vehiculos));
	    }	    
	}	
	
	public function obtenerDestinatariosAjaxAction()
	{
	    if($this->identity())
	    {
	        $request = $this->getRequest();
	        $response = $this->getResponse();
	        
	        if ($request->isPost())
	        {
	            $data = $request->getPost();
	            
	            $cod_traslado =  $data['cod_traslado'];
	            
	            $destinatarios = $this->destinatarioTable->obtenerDestinatariosPorCodTraslado($cod_traslado);
	            
	            $response->setContent(\Laminas\Json\Json::encode($destinatarios));
	        }
	        
	        return $response;
	    }
	}
	
	public function guardarDestinatarioAjaxAction()
	{
	    
	    if($this->identity())
	    {
	        $request = $this->getRequest();
	        $response = $this->getResponse();
	        
	        if ($request->isPost())
	        {
	            $data = $request->getPost();
	            
	            $errorMessage = "";
	            
	            $destinatario = new Destinatario();
	            $destinatario->cod_destinatario    = $data['destinatario']['cod_destinatario'];
	            $destinatario->nombre              = $data['destinatario']['nombre'];
	            $destinatario->distrito            = $data['destinatario']['distrito'];
	            $destinatario->numero_guia         = $data['destinatario']['numero_guia'];
	            $destinatario->hora_llegada        = $data['destinatario']['hora_llegada'];
	            $destinatario->temperatura_llegada = $data['destinatario']['temperatura_llegada'];
	            $destinatario->humedad_relativa_llegada = $data['destinatario']['humedad_relativa_llegada'];
	            $destinatario->hora_entrega        = $data['destinatario']['hora_entrega'];
	            $destinatario->temperatura_entrega = $data['destinatario']['temperatura_entrega'];
	            $destinatario->humedad_relativa_entrega= $data['destinatario']['humedad_relativa_entrega'];
	            $destinatario->hora_salida         = $data['destinatario']['hora_salida'];
	            $destinatario->cod_traslado        = $data['destinatario']['cod_traslado'];
	            
	            //var_dump($data);
                
                try
                {
                    $cod_destinatario = $this->destinatarioTable->guardar($destinatario);                    
                }
                catch (\Exception $e)
                {
                    $errorMessageCode = $e->getCode();
                    
                    if($errorMessageCode == "23000"){
                        //$errorMessage= " Verifique que los datos no esten duplicados.";
                        $errorMessage= $e->getMessage();
                    }else{
                        $errorMessage = $e->getMessage();
                    }                    
                }                
                
                if ($errorMessage == "")
                {
                    $response->setContent(\Laminas\Json\Json::encode([
                        'response' => true,
                        'successMessage' => "El destinatario ha sido guardado correctamente.",
                        'cod_destinatario' => $cod_destinatario
                    ]));
                }
                else
                {
                    $message = "Han ocurrido errores.";
                    $response->setContent(\Laminas\Json\Json::encode([
                        'response' => false,
                        'errorCode' => $errorMessageCode,
                        'errorMessage' => $message. $errorMessage,
                        'cod_destinatario' => 0
                    ]));
                }  
	            
	        }
	        return $response;
	    }
	    
	}
	
	public function eliminarDestinatarioAjaxAction()
	{
	    
	    if($this->identity())
	    {
	        $request = $this->getRequest();
	        $response = $this->getResponse();
	        
	        if ($request->isPost())
	        {
	            $data = $request->getPost();
	            $cod_destinatario = (int) $data['destinatario']['cod_destinatario'];
	            
	            $errorMessage = "";
                    
                try
                {                    
                    $this->destinatarioTable->eliminar($cod_destinatario);
                }
                catch (\Exception $e)
                {
                    $errorMessageCode = $e->getCode();
                    $errorMessage = $e->getMessage();                    
                }
                
                if ($errorMessage == "")
                {
                    $response->setContent(\Laminas\Json\Json::encode([
                        'response' => true,
                        'successMessage' => "El destinatario ha sido eliminado correctamente.",
                        'cod_destinatario' => $cod_destinatario
                    ]));
                }
                else
                {
                    $response->setContent(\Laminas\Json\Json::encode([
                        'response' => false,
                        'errorCode' => $errorMessageCode,
                        'errorMessage' => $errorMessage,
                        'cod_destinatario' => 0
                    ]));
                }            
	        }
	        return $response;
	    }
	    
	}
		
	private function getDatosUsuario()
	{
		if($this->identity())
		{		
		    return $Usuario = $this->usuarioTable->obtenerUsuario($this->identity()['cod_usuario']);
		}
		
		return;
	}
	
}






