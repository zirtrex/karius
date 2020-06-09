<?php
namespace Users\Controller;
 
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;
use Interop\Container\ContainerInterface;
use Admin\Model\UsuarioTable;
use Users\Form\EditarUsuarioForm;
use Users\Form\CambiarClaveForm;
use Users\Form\SubirImagenForm;
use Admin\Entity\Usuario;

 
class PerfilController extends AbstractActionController
{    
    private $container;
    private $usuarioTable;
    // The image manager.
    private $imageManager;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        // Set alternative layout
        $this->layout()->setTemplate('layout/admin');
        return $response;
    }
    
    public function __construct(ContainerInterface $container, UsuarioTable $usuarioTable)
    {
        $this->container = $container;
        $this->usuarioTable = $usuarioTable;
        $this->imageManager = null;
    }
    
    public function indexAction()
    {       
        if ($this->identity())
        { 
            $formManager = $this->container->get('FormElementManager');            
            $editarUsuarioForm = $formManager->get(EditarUsuarioForm::class);
            
            $cambiarClaveForm = new \Users\Form\CambiarClaveForm($this->getRequest()->getBaseUrl() . '/captcha/');
            
            $subirImagenForm = new \Users\Form\SubirImagenForm();
            
            $cod_usuario = $this->identity()["cod_usuario"];
            $usuario = $this->usuarioTable->obtenerUsuario($cod_usuario);
    		$editarUsuarioForm->populateValues($usuario->getArrayCopy());            		

            
            $viewModel = new ViewModel([
                'editarUsuarioForm' => $editarUsuarioForm,
        		'cambiarClaveForm' => $cambiarClaveForm,
        		'subirImagenForm' => $subirImagenForm
            ]);
            
            return $viewModel;
        }

        return $this->redirect()->toRoute('ingresar');
        
    }
	
    //Editar los datos del usuario
    public function editarPerfilAction()
    {
    	if ($this->identity()){
    		$formManager = $this->container->get('FormElementManager');            
            $editarUsuarioForm = $formManager->get(EditarUsuarioForm::class);
    		
    		$cambiarClaveForm = new \Users\Form\CambiarClaveForm($this->getRequest()->getBaseUrl() . '/captcha/');
    		
    		$subirImagenForm = new \Users\Form\SubirImagenForm(); 		
    		
    		$cod_usuario = $this->identity()['cod_usuario'];
    		$usuario = $this->usuarioTable->obtenerUsuario($cod_usuario);
    		$editarUsuarioForm->populateValues($usuario->getArrayCopy());
    		
    		$request = $this->getRequest();
    	
    		if($request->isPost()){    	
    			$editarUsuarioForm->setData($request->getPost());
    			 
    			if ($editarUsuarioForm->isValid()){
    			    $usuario = new Usuario();
    				$usuario->exchangeArray($editarUsuarioForm->getData());
				        
			        if($this->usuarioTable->guardarUsuario($usuario)){
				        $this->flashMessenger()->addInfoMessage('¡Sus datos han sido cambiados correctamente!');
				        return $this->redirect()->toRoute('perfil');
				    }    				    	
    			}    	
    		}
    	
    		$viewModel= new ViewModel(array(
    				'editarUsuarioForm' => $editarUsuarioForm,
    				'cambiarClaveForm' => $cambiarClaveForm
    		));
    	
    		$viewModel->setTemplate('users/perfil/editar-perfil.phtml');
    		return $viewModel;
    	}    	 
    	return $this->redirect()->toRoute('ingresar');
    } 
	
    //Cambiar la clave del usuario
	public function cambiarClaveAction()
    {
    	if ($this->identity())
    	{
    		$formManager = $this->container->get('FormElementManager');            
            $editarUsuarioForm = $formManager->get(EditarUsuarioForm::class);
    		
    		$cambiarClaveForm = new \Users\Form\CambiarClaveForm($this->getRequest()->getBaseUrl() . '/captcha/');
    		
    		$subirImagenForm = new \Users\Form\SubirImagenForm(); 		
    		
    		$cod_usuario = $this->identity()['cod_usuario'];
    		$usuario = $this->usuarioTable->obtenerUsuario($cod_usuario);
    		$editarUsuarioForm->populateValues($usuario->getArrayCopy());
    		
    		$request = $this->getRequest();
    		
    		if ($request->isPost()){      		      			
    			$cambiarClaveForm->setData($request->getPost());
    			$cambiarClaveForm->setInputFilter(new \Users\Form\Filter\CambiarClaveFilter());
    			
    			if ($cambiarClaveForm->isValid()){
    				$data = $cambiarClaveForm->getData();
    				
    				$claveActual = (string) $data['anteriorClave']; //md5($data['anteriorClave']);
    				$nuevaClave = (string) $data['nuevaClave']; //md5($data['nuevaClave']);
    				
    				$usuario = $this->usuarioTable->obtenerUsuario($cod_usuario);
    				
    				$claveOriginal = (string) $usuario->clave;
    
    				if ($claveOriginal == $claveActual){    					
    					$usuario->clave = $nuevaClave;
    					
    					if($this->usuarioTable->guardarClave($usuario)){
    						$this->flashMessenger()->addInfoMessage('¡Su clave ha sido cambiada correctamente, vuelva a ingresar por favor!');    						
    						return $this->redirect()->toRoute('salir');
    					}    					
    				} else { 
    					$this->flashMessenger()->addErrorMessage('¡La clave proporcionada no es correcta');
    					return $this->redirect()->toRoute('perfil', array('action' => 'cambiar-clave'));
    				}
    			}
    			$error = true;
    		}
    		
    		$viewModel= new ViewModel([
				'editarUsuarioForm' => $editarUsuarioForm,
				'cambiarClaveForm' => $cambiarClaveForm,
				'subirImagenForm' => $subirImagenForm
    		]);
    		 
    		$viewModel->setTemplate('users/perfil/editar-perfil.phtml');
    		return $viewModel;
    
    	}
    	return $this->redirect()->toRoute('ingresar');
    }

    //Subir imagen a través de ajax
    public function subirImagenAjaxAction()
    {
        if ($this->identity()) {
        	$response = $this->getResponse();
        	$request = $this->getRequest();

			if ($request->isPost()) {
                $form = new \Users\Form\SubirImagenForm();
				$data = array_merge_recursive(
				    $request->getPost()->toArray(),
				    $request->getFiles()->toArray()
				);
				
				$form->setInputFilter(new \Users\Form\Filter\SubirImagenFilter());

				$form->setData($data);
                      
                if ($form->isValid()) {   
				    $data = $form->getData();
				    
				    $File = $data['imagen'];
				    
				    $destination =  './public/img/perfil/';				    	
				    	
				    $extension = pathinfo($File['name'], PATHINFO_EXTENSION);
				    	
				    $nombreImagen = $this->identity()['usuario'] . "." . $extension;
				    
				    //$nombreImagen = time() . substr(md5(microtime()), 0, rand(5, 12)) . "." . $extension;
				    	
				    if (rename($destination . $File['name'], $destination.$nombreImagen)){
    					//guardar la imagen del usuario
    					$usuario = $this->usuarioTable->obtenerUsuario($this->identity()["cod_usuario"]);    					
    				    
    					$usuario->imagen_perfil	= $nombreImagen;
    					
    					$updated = $this->usuarioTable->guardarUsuarioImagen($usuario);
    				
    					if ($updated) {
    						$response->setContent(\Laminas\Json\Json::encode(array('response' => true, 'message'=>'Imagen guardada correctamente.')));
    					} else {					    
    						$response->setContent(\Laminas\Json\Json::encode(array('response' => false, 'message'=>'Fallo el guardado de la imagen.')));
    					}
				    }
				} else {
				    //var_dump($form->get('imagen')); return;
				    $response->setContent(\Laminas\Json\Json::encode(array('response' => false, 'errores'=> $form->get('imagen')->getMessages())));
				}
			}
			
            return $response;
        }
    }
}









