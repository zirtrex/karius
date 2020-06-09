<?php

namespace Users\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;
use Interop\Container\ContainerInterface;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mail\Message;
use Users\Form\CambiarClaveForm;
use Users\Form\OlvidarClaveForm;
use Users\Form\Filter\CambiarClaveFilter;
use Users\Form\Filter\OlvidarClaveFilter;
use Admin\Model\UsuarioTable;
use Admin\Entity\Usuario;




class ReestablecerClaveController extends AbstractActionController
{
    private $container;
    private $dbAdapter;
    private $usuarioTable;
    private $smtpTransport;
    private $imageManager;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        // Set alternative layout
        $this->layout()->setTemplate('layout/login');
        return $response;
    }
    
    public function __construct(ContainerInterface $container, $dbAdapter, UsuarioTable $usuarioTable, Smtp $mailFactory)
    {
        $this->container = $container;
        $this->dbAdapter = $dbAdapter;
        $this->usuarioTable = $usuarioTable;
        $this->smtpTransport = $mailFactory;
    }
    
	//Cargamos el formulario para enviarle un correo al usuario confimando su cambio de contraseña.
    public function indexAction()
    {
    	$form = new OlvidarClaveForm($this->getRequest()->getBaseUrl() . '/captcha/');
    	
    	$request = $this->getRequest();
          
        if($request->isPost())
        {        	        	
        	$form->setInputFilter(new OlvidarClaveFilter($this->dbAdapter));
        	$form->setData($request->getPost());
        	
        	if ($form->isValid()){
        		//\Zend\Debug\Debug::dump($form->getData());
        		
        		$data = $form->getData();
        		
        		$correo = $data['correo'];         		
        		
        		$usuario = 	$this->usuarioTable->obtenerUsuarioPorCorreo($correo);        				
        		
        		if($usuario){
        			
        			$usuario->token_registro = md5(uniqid(mt_rand(), true)); // \Zend\Debug\Debug::dump($usuario);
        			
        			$this->usuarioTable->guardarUsuario($usuario);
        			
        			$this->enviarCorreoConfirmacionCambioClave($usuario, $correo);
        			$this->flashMessenger()->addInfoMessage('¡Se le ha enviado las instrucciones para restablecer su clave a su correo: ' .$correo. '!');
        			return $this->redirect()->toRoute('reestablecer-clave');
        		}
        		else
        		{
        			$this->flashMessenger()->addErrorMessage('¡El correo ingresado no tiene asignado ningún usuario, intente de nuevo!');
        			return $this->redirect()->toRoute('reestablecer-clave');
        		}
        	}
        }
        
        return new ViewModel(array(        		
        	'form' => $form
        ));
        
    }
	
	//Enviamos el correo a través del mail.transport
    public function enviarCorreoConfirmacionCambioClave($usuario, $correo)
    {
    	try
    	{   //$this->getRequest()->getServer();
    	    
    		$hostname = $_SERVER['HTTP_HOST'];
    		 
    		$fullLink = "http://" . $hostname . $this->url()->fromRoute('reestablecer-clave', ['action' => 'confirmar-cambio-clave', 'token' => $usuario->token_registro]);
	    	
    		$message = new Message();
	    	
	    	$message->addTo($correo)
	    		->addFrom('rcontreras@zirtrex.net')
	    		->setSubject('Por favor, confirma tu solicitud para cambio de contraseña!')
		    	->setBody('Hola, '. $usuario->nombres .". Por favor, sigue el siguiente enlace: " .$fullLink. " para confirmar tu solicitud de cambio de clave.");
    	
	    	$transport = $this->smtpTransport;
	    	
	    	$transport->send($message);
	    	return true;
    	}
    	catch (\Exception $ex)
    	{
	    	throw new \Exception($ex);
	    	return false;
    	}
    }
    
	//Recibimos la confirmación del usuario para el cambio de clave
    public function confirmarCambioClaveAction()
    {
        
    	    $token = $this->params()->fromRoute('token');
    	    
    	    try{
                $usuario = $this->usuarioTable->obtenerUsuarioPorToken($token);
    	    } catch (\Exception $e) {
    	        $this->flashMessenger()->addErrorMessage($e->getMessage());
    	        return $this->redirect()->toRoute('reestablecer-clave');
    	    }
    	
        	if ($usuario) {        	    
        		$form = new CambiarClaveForm($this->getRequest()->getBaseUrl() . '/captcha/');    		
        		$form->get('token')->setValue($token);    		
        		
        		$request = $this->getRequest();
        		
        		if ($request->isPost()) {
        		    
        			$form->setInputFilter(new CambiarClaveFilter());
        			$form->setValidationGroup(array('nuevaClave', 'confirmarNuevaClave'));
        			$form->setData($request->getPost());
        			 
        			if ($form->isValid()){
        			    
        				$formData = $form->getData();
        				
        				$clave = $formData['nuevaClave'];
        				  				
        				$usuario->clave = $clave;
        				
        				if($this->usuarioTable->guardarClave($usuario)){
        					$usuario->token_registro = md5(uniqid(mt_rand(), true)); //cambiamos el token para que se inválide en una segunda confirmación
        					$this->usuarioTable->guardarUsuario($usuario);
        					$this->enviarConfirmacionCambioClave($usuario, $usuario->correo);
        					$this->flashMessenger()->addInfoMessage('¡Su clave ha sido cambiada!');
        					return $this->redirect()->toRoute('ingresar');
        				} else {
        					$this->flashMessenger()->addErrorMessage('¡Ha ocurrido un error al guardar su nueva clave, intente de nuevo!');
        					return $this->redirect()->toRoute('reestablecer-clave', array('action' => 'confirmar-cambio-clave', 'token' => $token));
        				}
        			}
        		}    		
        		
        		return new ViewModel([
        				'form' => $form,
        				'token' => $token
        		]);
        	}
        
    }
    
    public function enviarConfirmacionCambioClave($usuario, $correo)
    {
    	try {

	    	$hostname    = $_SERVER['HTTP_HOST'];
	    	
	    	$fullLink = "http://" . $hostname . $this->url()->fromRoute('ingresar');	    
	    	
	    	$message = new Message();
	    	
	    	$message->addTo($correo)
	    		->addFrom('rcontreras@grupoparada.com')
	    		->setSubject('Tu clave ha sido cambiada!')
	    		->setBody('Hola de nuevo '.$usuario->nombres. ' Tu clave ha sido cambiada. <br> Por favor, sigue el siguiente enlace: ' . $fullLink . ', para ingresar al sistema.'
	    	);
	    	
	    	$transport = $this->smtpTransport;
	    	
	    	$transport->send($message);
	    	return true;
    	}
    	catch (\Exception $ex)
    	{
    		throw new \Exception($ex);
    		return false;
    	}
    }
   
}















