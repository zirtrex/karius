<?php
namespace Users\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Laminas\Mvc\MvcEvent;
use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp;
use Admin\Factory\MailFactory;
use Admin\Model\UsuarioTable;
use Admin\Model\Miscellanea;
use Admin\Entity\Usuario;


class RegistroController extends AbstractActionController
{
    private $dbAdapter;
    private $usuarioTable;
    private $smtpTransport;
    
    public function onDispatch(MvcEvent $e)
    {
        $response = parent::onDispatch($e);
        $this->layout()->setTemplate('layout/login');
        return $response;
    }
    
    public function __construct(UsuarioTable $usuarioTable, $dbAdapter, Smtp $mailFactory)
    {
        $this->usuarioTable = $usuarioTable;
        $this->dbAdapter = $dbAdapter;
        $this->smtpTransport = $mailFactory;
    }
    
    public function indexAction()
    {
        if (!$this->identity()) {
            $form = new \Users\Form\RegistroForm();
            
            $request = $this->getRequest();
            
            if ($request->isPost()) {
                
                $form->setInputFilter(new \Users\Form\Filter\RegistroFilter($this->dbAdapter));
                $form->setData($request->getPost());
                
                $secret = '6LfTHyoUAAAAAG1DhEEmXyA5uNZdKEVWZf8j7RMQ';
                
                $gRecaptchaResponse = isset($_POST["g-recaptcha-response"])? $_POST["g-recaptcha-response"] : null;
                	
                $recaptcha = new \ReCaptcha\ReCaptcha($secret);
                	
                $resp = $recaptcha->verify($gRecaptchaResponse, $_SERVER['REMOTE_ADDR']);
                
                if ($resp->isSuccess()) {
                
                    if ($form->isValid()) {      
                        $data = $form->getData();
                        
                        $usuario = new Usuario();
                        
                        $usuario->exchangeArray($data);
                        
                        $usuario->rol = 'usuario';
                        $usuario->token_registro = md5(uniqid(mt_rand(), true));
                        $usuario->fecha_registro = gmdate("Y-m-d H:i:s", Miscellanea::getHoraLocal(-5));
                        $usuario->correo_confirmado = '0';
                        
                        $this->usuarioTable->registrarUsuario($usuario);
                        
                        $this->enviarCorreoConfirmacion($usuario);                        
                        $this->flashMessenger()->addInfoMessage('Se ha enviado un mensaje a '.$usuario->correo. ', por favor confirmar. Si no lo ha recibido aún, verifique la carpeta de spam');
                        return $this->redirect()->toRoute('registro');
                    }
                    //\Zend\Debug\Debug::dump($form->getInputFilter()->getMessages());
                } else {
                    $form->get('registrarse')->setMessages(array('No ha sobrepasado nuestros filtros de seguridad, vuelva a intentarlo por favor.', 'Verifique que tenga javascript activo.'));
                }
            }
            
            return new ViewModel([
                'form' => $form
            ]);
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
    
    public function confirmarCorreoAction()
    {
        $token = $this->params()->fromRoute('token');
        
        try {            
            $usuario = $this->usuarioTable->obtenerUsuarioPorToken($token);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage("Token inválido. Contacta al administrador");
        }
            
        if (isset($usuario)) {
            $usuario->token_registro = md5(uniqid(mt_rand(), true)); // cambiar el token
            $usuario->correo_confirmado = "1";
            $this->usuarioTable->guardarUsuario($usuario);
            $this->flashMessenger()->addInfoMessage("Correo validado correctamente.");
        }        
    
        return new ViewModel([]); 
    }
    
    private function enviarCorreoConfirmacion($usuario)
    {
        $hostname    = $_SERVER['HTTP_HOST'];
        $fullLink = "http://" . $hostname . $this->url()->fromRoute('confirmar-correo', [
            'controller' => 'registro',
            'action' => 'confirmar-correo',
            'token' => $usuario->token_registro]);
        
        $transport = $this->smtpTransport;
        
        $message = new Message(); 
        $this->getRequest()->getServer();
        
        $message->addTo($usuario->correo)
            ->addFrom('rcontreras@zirtrex.net')
            ->setSubject('Por favor, confirma tu correo (KyL)!')
            ->setBody("Por favor, click en el enlace para confirmar tu registro => " . $fullLink );
        
        $transport->send($message);
    }
}

