<?php
namespace Users;

use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            
            'registro' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/registro[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\RegistroController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            
            'ingresar' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/ingresar[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            
            'salir' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/salir[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'salir'
                    ],
                ],
            ],
            
            'perfil' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/perfil[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\PerfilController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            
            'reestablecer-clave' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/reestablecer-clave[/:action][/token/:token]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
                        'token' => '[a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\ReestablecerClaveController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            
            'confirmar-correo' =>[
                'type'    => Segment::class,
                'options' => array(
                    'route'    => '/confirmar-correo[/:token]',
                    'constraints' => [
                        'token' => '[a-zA-Z0-9_-]*'
                    ],
                    'defaults' => array(
                        'controller'    => Controller\RegistroController::class,
                        'action'        => 'confirmarCorreo',
                    ),
                ),
            ],
        ],
        
    ],
    
    'service_manager' => [
        'factories' => [
            // Register the ImageManager service
            Service\ImageManager::class => InvokableFactory::class,
        ],
    ],
    
    'controllers' => [
        'factories' => [
            \Users\Controller\AuthController::class => \Users\Factory\Controller\AuthControllerServiceFactory::class,
        ],
        
        'invokables' => [
            
        ]
    ],
    
    'view_manager' => [
        'template_path_stack' => [
            'users' => __DIR__ . '/../view'
        ],
    ],
];