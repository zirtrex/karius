<?php
namespace Traslado;

use Laminas\Router\Http\Segment;


return [
    'router' => [
        'routes' => [
            
            'traslado' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/traslado[/:action][/:cod_traslado][/:cod_cliente][/:cod_conductor][/:cod_vehiculo][/page/:page][/orderby/:orderby][/:order]',
                    'constraints' => [
                        'action'            => '[a-zA-Z][a-zA-Z0-9_-]*',
                        /*'cod_cliente'       => '[a-zA-Z0-9_-]*',*/
                        'cod_traslado'      => '[0-9]+',
                        'cod_cliente'       => '[0-9]+',
                        'cod_conductor'     => '[0-9]+',
                        'cod_vehiculo'      => '[0-9]+',                        
                        'page'              => '[0-9]+',
                        'orderby'           => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order'             => 'ASC|DESC'
                    ],
                    'defaults' => [
                        'controller'    => Controller\TrasladoController::class,
                        'action'        => 'index'
                    ]
                ]
            ],
            
            'destinatario' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/traslado/destinatario[/:action][/:cod_traslado]',
                    'constraints' => [
                        'action'            => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'cod_traslado'      => '[0-9]+'
                    ],
                    'defaults' => [
                        'controller'    => Controller\TrasladoController::class,
                        'action'        => 'index'
                    ]
                ]
            ]

        ]
    ],
    
    'service_manager' => [],
    
    'controllers' => [
        'factories' => []    
    ],
    
    'view_manager' => array(
        'template_path_stack' => array(
            'traslado' => __DIR__ . '/../view'
        )
    )
];
