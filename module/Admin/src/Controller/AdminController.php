<?php

namespace Admin\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;
use Interop\Container\ContainerInterface;
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


class AdminController extends AbstractActionController 
{
    private $container;
    private $almacenTable;
    private $clienteTable;
    private $conductorTable;
    private $destinatarioTable;
    private $trasladoTable;
    private $usuarioTable;
    private $vehiculoTable;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        // Set alternative layout
        $this->layout()->setTemplate('layout/admin');
        return $response;
    }
    
    public function __construct(
        ContainerInterface $container,
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
    
    /*public function listarUsuariosAction()
    {
        
        $docentes = $this->requerimientoTable->obtenerUsuariosConRequerimientos(Miscellanea::SEMESTRE);
        
        return new ViewModel([
            'docentes' => $docentes,
            'messages' => $this->flashmessenger()->getMessages(),
            'errorMessages' => $this->flashmessenger()->getErrorMessages()
        ]);
    }
    
    //Muestra los cursos que tiene asignado un docente, es procesado cuando se inicia sesión
    public function listarCursosAction()
    {
        if($this->identity())
        {
            $codUsuario = (int) $this->params()->fromRoute('codusuario', 0);
            
            $cursos = $this->requerimientoTable->obtenerCursosByCodUsuarioAndSemestre($codUsuario, Miscellanea::SEMESTRE);
            
            return new ViewModel([
                'cursos' => $cursos,
                'codUsuario' => $codUsuario,
                'usuario' => $this->usuarioTable->obtenerUsuario($codUsuario),
                'messages' => $this->flashmessenger()->getMessages(),
                'errorMessages' => $this->flashmessenger()->getErrorMessages()
            ]);
        }
        
        return $this->redirect()->toRoute('ingresar');
    }
    
    public function listarPedidosAction()
    {
        
        $codUsuario         = (int) $this->params()->fromRoute('codusuario', 0);
        $semestre           = (String) $this->params()->fromRoute('semestre', Miscellanea::SEMESTRE);
        $codEscuela         = (int) $this->params()->fromRoute('codescuela', 0);
        $codCurso           = (int) $this->params()->fromRoute('codcurso', 0);
        
        if($codEscuela != 0){
            $escuela = $this->escuelaTable->obtenerEscuela($codEscuela);
        }else{
            $escuela = new Escuela();
            $escuela->nombreEscuela = "-";
        }        
        
        if($codCurso != null){
            $curso = $this->cursoTable->obtenerCurso($codCurso);
        }else{
            $curso = new Curso();
            $curso->nombreCurso = "Se listan los pedidos de todos los cursos";
        }
        
        //obtenerRequerimientosByCodigos($semestre = null, $codUsuario = null, $codEscuela = null, $codCurso = null, $codQuimico = null, $orderBy = array('codUsuario' => 'ASC'))
        $requerimientos = $this->requerimientoTable->obtenerRequerimientosByCodigosAndSemestre($semestre, $codUsuario, $escuela->codEscuela, $curso->codCurso);
        
        return new ViewModel([
            'codUsuario'        => $codUsuario,
            'usuario'           => $this->usuarioTable->obtenerUsuario($codUsuario),
            'semestre'          => $semestre,
            'codEscuela'        => $codEscuela,
            'escuela'           => $escuela,
            'codCurso'          => $codCurso,
            'curso'             => $curso,
            'requerimientos'    => $requerimientos,
            'messages'          => $this->flashmessenger()->getMessages(),
            'errorMessages'     => $this->flashmessenger()->getErrorMessages()
        ]);
    }*/
    
}
