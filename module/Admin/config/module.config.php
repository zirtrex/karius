<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;


return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => Controller\AdminController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'almacen' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/almacen[/:action][/cod_almacen/:cod_almacen][/cod_cliente/:cod_cliente][/page/:page][/orderby/:orderby][/:order]',
                    'constraints' => [
                        'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'cod_almacen'   => '[0-9]+',
                        'cod_cliente'   => '[0-9]+',
                        'page'          => '[0-9]+',
                        'orderby'       => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'         => 'ASC|DESC'
                    ],
                    'defaults' => [
                        'controller' => Controller\AlmacenController::class
                    ],
                ]
            ],
            'cliente' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/cliente[/:action][/:cod_cliente][/page/:page][/orderby/:orderby][/:order]',
                    'constraints' => [
                        'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'cod_cliente'   => '[0-9]+',                        
                        'page'          => '[0-9]+',
                        'orderby'       => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'         => 'ASC|DESC'
                    ],
                    'defaults' => [
                        'controller' => Controller\ClienteController::class, 
                        'action'     => 'index',
                    ],
                ]
            ],
            'conductor' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/conductor[/:action][/:cod_conductor][/page/:page][/orderby/:orderby][/:order]',
                    'constraints' => [
                        'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'cod_conductor' => '[0-9]+',                        
                        'page'          => '[0-9]+',
                        'orderby'       => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'         => 'ASC|DESC'
                    ],
                    'defaults' => [
                        'controller' => Controller\ConductorController::class,
                        'action'     => 'index',
                    ],
                ]
            ],
            'traslado_adm' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/traslado[/:action][/:cod_traslado][/page/:page][/orderby/:orderby][/:order]',
                    'constraints' => [
                        'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'cod_traslado'  => '[0-9]+',
                        'page'          => '[0-9]+',
                        'orderby'       => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'         => 'ASC|DESC'
                    ],
                    'defaults' => [
                        'controller' => Controller\TrasladoController::class,
                        'action'     => 'index',
                    ],
                ]
            ],
            'vehiculo' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/admin/vehiculo[/:action][/:cod_vehiculo][/page/:page][/orderby/:orderby][/:order]',
                    'constraints' => [
                        'action'        => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'cod_vehiculo'  => '[0-9]+',
                        'page'          => '[0-9]+',
                        'orderby'       => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'         => 'ASC|DESC'
                    ],
                    'defaults' => [
                        'controller' => Controller\VehiculoController::class,
                        'action'     => 'index',
                    ],
                ]
            ],
        ]
    ],
    
    'module_config' => array (
        'upload_location' => __DIR__ . '/../../../public/comprobantes'
    ),
    
    'service_manager' => [
        'factories' => [                       
            \Admin\Acl\Acl::class => function ($container) {
                $config = include __DIR__ . '/acl.config.php';
                return new \Admin\Acl\Acl ( $config );
            },
            \Laminas\Mail\Transport\Smtp::class => \Admin\Factory\MailFactory::class,
        ],
    ],
    
    'controllers' => [
        'factories' => [
            //Controller\ReporteController::class => InvokableFactory::class,
        ],
    ],
    
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'         => __DIR__ . '/../view/layout/layout.phtml',
            'layout/admin'          => __DIR__ . '/../view/layout/layout_admin.phtml',
            'layout/login'          => __DIR__ . '/../view/layout/layout_login.phtml',
            'admin/index/index'     => __DIR__ . '/../view/admin/index/index.phtml',
            'error/404'             => __DIR__ . '/../view/error/404.phtml',
            'error/index'           => __DIR__ . '/../view/error/index.phtml',
            'paginator' 			=> __DIR__ . '/../view/partial/paginator.phtml'
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    
    'navigation' => [
        'default' => [
            array(
                'label' => 'Inicio',
                'route' => 'home',
            ),
            array(
                'label' => 'Nuevo Traslado',
                'route' => 'traslado',
                'action' => 'nuevo-traslado',
            ),
            array(
                'label' => 'Mis Traslados',
                'route' => 'traslado',
                'action' => 'ver-traslados',
            ),            
        ],
        'admin_menu' => [
            array(
                'label' => 'Cliente',
                'route' => 'cliente',
            ),
            array(
                'label' => 'Conductor',
                'route' => 'conductor',
                'action' => 'index',
            ),
            array(
                'label' => 'Vehiculo',
                'route' => 'vehiculo',
                'action' => 'index',
            ),
            array(
                'label' => 'Traslado',
                'route' => 'traslado_adm',
                'action' => 'index',
            ),
        ],
    ],

        
];
