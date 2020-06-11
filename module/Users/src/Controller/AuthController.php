<?php

namespace Users\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\Mvc\MvcEvent;


class AuthController extends AbstractActionController
{
    protected $authService;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        // Set alternative layout
        $this->layout()->setTemplate('layout/login');
        return $response;
    }

    //inyectaremos authService via factory
    public function __construct(AuthenticationService $authService/*, UsuarioTable $usuarioTable*/)
    {
        $this->authService = $authService;
    }

    public function indexAction()
    {
        $form = new \Users\Form\LoginForm();

    	$request = $this->getRequest();

        if ($request->isPost()){
            
        	$form->setInputFilter(new \Users\Form\Filter\LoginFilter());
        	$form->setValidationGroup(array('usuario','clave'));
        	$form->setData($request->getPost());

        	$secret = '6Lc5EWkUAAAAAPL0Tlt0a-Y3DG5MF7CviSKI1DSu';

    	    $gRecaptchaResponse = isset($_POST["g-recaptcha-response"])? $_POST["g-recaptcha-response"] : null;

    	    $recaptcha = new \ReCaptcha\ReCaptcha($secret);

    	    //$resp = $recaptcha->verify($gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
    	    $resp = true; //Desabilito Recaptcha

    	    //if ($resp->isSuccess()) {
        	    if ($form->isValid()){
                	$formData = $form->getData();

                    $this->authService->getAdapter()->setIdentity($formData['usuario']);
                    $this->authService->getAdapter()->setCredential(($formData['clave']));//md5

                    $result = $this->authService->authenticate();

                    if ($result->isValid()){
                        $resultRow = $this->authService->getAdapter()->getResultRowObject();

                        $this->authService->getStorage()->write([
                             'cod_usuario'	=> $resultRow->cod_usuario,
                             'rol'          => $resultRow->rol,
                             'usuario'   	=> $formData['usuario'],
                             'ip_address' 	=> $this->getRequest()->getServer('REMOTE_ADDR'),
                             'user_agent'	=> $request->getServer('HTTP_USER_AGENT')
                        ]);

                        return $this->redirect()->toRoute('home');

                    } else {
                        $this->flashMessenger()->addErrorMessage('¡Nombre de usuario o clave incorrecta!');
                        return $this->redirect()->toRoute('ingresar');
                    }

                } else {
                    //throw new \Exception("Datos no validados correctamente.");
                }
    	    /*} else {
    	        $form->get('ingresar')->setMessages(array('No ha sobrepasado nuestros filtros de seguridad, vuelva a intentarlo por favor.', 'Verifique que tenga javascript activo.'));
    	    }*/
        }

        return new ViewModel([
			'form' => $form
        ]);

    }

    public function salirAction()
    {
        $this->authService->getStorage()->clear();

        $this->flashmessenger()->addInfoMessage("Has salido del sistema!");

        return $this->redirect()->toRoute('ingresar');
    }
}
