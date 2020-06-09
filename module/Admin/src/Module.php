<?php
namespace Admin;

use Laminas\Authentication\AuthenticationService;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;
use Laminas\Session\Validator\HttpUserAgent;
use Laminas\Session\Validator\RemoteAddr;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\ResultSet;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Admin\Acl\Acl;
use Laminas\ServiceManager\Factory\InvokableFactory;


class Module implements ConfigProviderInterface
{
    const VERSION = '1.0-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return [
            'factories' => [
                Model\AlmacenTable::class => function ($container) {
                    $tableGateway = $container->get(Model\AlmacenTableGateway::class);
                    return new Model\AlmacenTable($tableGateway);
                },
                Model\AlmacenTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entity\Almacen());
                    return new TableGateway('almacen', $dbAdapter, null, $resultSetPrototype);
                },
                
                Model\ClienteTable::class => function ($container) {
                    $tableGateway = $container->get(Model\ClienteTableGateway::class);
                    return new Model\ClienteTable($tableGateway);
                },
                Model\ClienteTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entity\Cliente());
                    return new TableGateway('cliente', $dbAdapter, null, $resultSetPrototype);
                },                
                
                Model\ConductorTable::class => function ($container) {
                    $tableGateway = $container->get(Model\ConductorTableGateway::class);
                    return new Model\ConductorTable($tableGateway);
                },
                Model\ConductorTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entity\Conductor());
                    return new TableGateway('conductor', $dbAdapter, null, $resultSetPrototype);
                },
                
                Model\DestinatarioTable::class => function ($container) {
                    $tableGateway = $container->get(Model\DestinatarioTableGateway::class);
                    return new Model\DestinatarioTable($tableGateway, $container);
                },
                Model\DestinatarioTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entity\Destinatario());
                    return new TableGateway('destinatario', $dbAdapter, null, $resultSetPrototype);
                }, 
                
                Model\TrasladoTable::class => function ($container) {
                    $tableGateway = $container->get(Model\TrasladoTableGateway::class);
                    return new Model\TrasladoTable($tableGateway);
                },
                Model\TrasladoTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entity\Traslado());
                    return new TableGateway('traslado', $dbAdapter, null, $resultSetPrototype);
                },
                
                Model\UsuarioTable::class => function ($container) {
                    $tableGateway = $container->get(Model\UsuarioTableGateway::class);
                    return new Model\UsuarioTable($tableGateway);
                },
                Model\UsuarioTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entity\Usuario());
                    return new TableGateway('usuario', $dbAdapter, null, $resultSetPrototype);
                },                
                
                Model\VehiculoTable::class => function ($container) {
                    $tableGateway = $container->get(Model\VehiculoTableGateway::class);
                    return new Model\VehiculoTable($tableGateway);
                },
                Model\VehiculoTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Entity\Vehiculo());
                    return new TableGateway('vehiculo', $dbAdapter, null);
                },
                
                //Smtp::class => Factory\MailFactory::class
                
            ]
        ];
    }
    
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => InvokableFactory::class,
                
                Controller\AdminController::class => function ($container) {
                    return new Controller\AdminController(
                        $container,
                        $container->get(Model\AlmacenTable::class),
                        $container->get(Model\ClienteTable::class),
                        $container->get(Model\ConductorTable::class),
                        $container->get(Model\DestinatarioTable::class),
                        $container->get(Model\TrasladoTable::class),
                        $container->get(Model\UsuarioTable::class),
                        $container->get(Model\VehiculoTable::class)
                    );
                },
                
                Controller\AlmacenController::class => function ($container) {
                    return new Controller\AlmacenController(
                        $container,
                        $container->get(Model\AlmacenTable::class),
                        $container->get(Model\ClienteTable::class),
                        $container->get(Model\UsuarioTable::class)
                    );
                },
                
                Controller\ClienteController::class => function ($container) {
                    return new Controller\ClienteController(
                         $container,
                         $container->get(Model\AlmacenTable::class),
                         $container->get(Model\ClienteTable::class),
                         $container->get(Model\UsuarioTable::class)
                    );
                },
                
                Controller\ConductorController::class => function ($container) {
                    return new Controller\ConductorController($container, $container->get(Model\ConductorTable::class));
                },
                
                Controller\VehiculoController::class => function ($container) {
                    return new Controller\VehiculoController($container, $container->get(Model\VehiculoTable::class));
                }
            ]
        ];
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'factories' => [
                View\Helper\UsuarioHelper::class => function ($container) {
                    $usuarioHelper = new View\Helper\UsuarioHelper($container);
                    return $usuarioHelper;
                }
            ],
            'aliases' => [
                'usuario_helper' =>  View\Helper\UsuarioHelper::class
            ]
        );
    }
    
    public function onBootstrap(MvcEvent $mvcEvent)
    {       
        $this->bootstrapSession($mvcEvent);
        
        $this->auth = $mvcEvent->getApplication()
                                ->getServiceManager()
                                ->get(AuthenticationService::class);
        
        $mvcEvent->getApplication()->getEventManager()->attach('route', array($this, 'onRoute'), -150);
    
        if ($this->auth->hasIdentity()) {
            $mvcEvent->getViewModel()->setVariable('authIdentity', $this->auth->getIdentity());            
        } else {}
    }
    
    public function onRoute(\Laminas\EventManager\EventInterface $e) {
        
        $application = $e->getApplication();
        $routeMatch = $e->getRouteMatch();
        $sm = $application->getServiceManager();
        $auth = $sm->get(AuthenticationService::class);
        $acl = $sm->get(Acl::class);
        // everyone is guest until logging in
        $role = \Admin\Acl\Acl::DEFAULT_ROLE; // The default role is guest $acl
        
        if ($auth->hasIdentity())
        {
            $user = $auth->getIdentity();
            $role = ($auth->getStorage()->read()['rol'] == 'admin') ? 'admin' : 'usuario';
        }
        
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');
        
        if (!$acl->hasResource($controller)){
            throw new \Exception('Resource ' . $controller . ' not defined');
        }
            
        if (!$acl->isAllowed($role, $controller, $action))
        {
            $response = $e->getResponse();
            
            if($auth->hasIdentity())
            {
                if($role == "admin"){
                    
                    $url = $e->getRouter()->assemble(array(), array('name' => 'admin')); // Route que se debe dirigir si tiene sesión y es admin
                    
                    $response->getHeaders()->addHeaders(array(
                        array('Location' => $url)
                    ));
                    $response->setStatusCode(302);
                    $response->sendHeaders();
                    exit;
                }else{
                    $url = $e->getRouter()->assemble(array(), array('name' => 'traslado')); // Route que se debe dirigir si tiene sesión y es usuario
                    
                    $response->getHeaders()->addHeaders(array(
                        array('Location' => $url)
                    ));
                    $response->setStatusCode(302);
                    $response->sendHeaders();
                    exit;
                }
            }else{
                
                $url = $e->getRouter()->assemble(array(), array('name' => 'ingresar')); // Route que se tiene que dirigir si no tiene sesión
                
                $response->getHeaders()->addHeaders(array(
                    array('Location' => $url)
                ));
                $response->setStatusCode(302);
                $response->sendHeaders();
                exit;
            }
        }
    }
    
    private function bootstrapSession($e)
    {
        $session = $e->getApplication()
        ->getServiceManager()
        ->get(SessionManager::class);
        
        $session->start();
        
        $container = new Container('session', $session);
        
        if (isset($container->init)) {
            return;
        }
        
        $request = $e->getRequest();
        $session->regenerateId(true);
        $container->init = 1;
        
        $container->remoteAddr = $request->getServer()->get('REMOTE_ADDR');
        $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');
        
        $config = $e->getApplication()
        ->getServiceManager()
        ->get('config');
        
        if (! isset($config['session'])) {
            return;
        }
        if (! isset($config['session_validators'])) {
            return;
        }
        $chain = $session->getValidatorChain();
        
        foreach ($config['session_validators'] as $validator) {
            switch ($validator) {
                case HttpUserAgent::class:
                    $validator = new $validator($container->httpUserAgent);
                    break;
                case RemoteAddr::class:
                    $validator = new $validator($container->remoteAddr);
                    break;
                default:
                    $validator = new $validator();
            }
            $chain->attach('session.validate', [
                $validator,
                'isValid'
            ]);
        }
    }
    
}
