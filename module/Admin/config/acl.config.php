<?php

return array(
    'acl' => array(
        'roles' => array(
            'invitado' => null,
            'usuario' => null,
            'admin' => null
        ),
        'resources' => array(
            'allow' => array(
                
                Admin\Controller\IndexController::class=> [
                    'index' => [
                        'invitado'
                    ]
                ],
                
                Admin\Controller\AdminController::class=> [
                    'index' => [
                        'admin'
                    ]
                ],
                
                Admin\Controller\AlmacenController::class=> [
                    'index' => [
                        'admin'
                    ],
                    'agregar-almacen' => [
                        'admin'
                    ],
                    'editar-almacen' => [
                        'admin'
                    ],
                    'eliminar-almacen' => [
                        'admin'
                    ]
                ],
                
                Admin\Controller\ClienteController::class=> [
                    'index' => [
                        'admin'
                    ],
                    'agregar-cliente' => [
                        'admin'
                    ],
                    'editar-cliente' => [
                        'admin'
                    ],
                    'eliminar-cliente' => [
                        'admin'
                    ]
                ],
                
                Admin\Controller\ConductorController::class=> [
                    'index' => [
                        'admin'
                    ],
                    'agregar-conductor' => [
                        'admin'
                    ],
                    'editar-conductor' => [
                        'admin'
                    ],
                    'eliminar-conductor' => [
                        'admin'
                    ]
                ],
                
                Admin\Controller\TrasladoController::class => array(
                    'index' => array(
                        'admin'
                    ),
                    'ver-traslados' => array(
                        'admin'
                    ),
                    'ver-pedidos' => array(
                        'admin'
                    ),
                    'nuevo-traslado' => array(
                        'admin'
                    ),
                    'editar-traslado' => array(
                        'admin'
                    ),
                    'agregar-destinatarios' => array(
                        'admin'
                    ),
                    'guardar-traslado-ajax' => array(
                        'admin'
                    ),
                    'obtener-clientes-ajax' => array(
                        'admin'
                    ),
                    'obtener-almacenes-ajax' => array(
                        'admin'
                    ),
                    'obtener-conductores-ajax' => array(
                        'admin'
                    ),
                    'obtener-vehiculos-ajax' => array(
                        'admin'
                    ),
                    'obtener-destinatarios-ajax' => array(
                        'admin'
                    ),
                    'guardar-destinatario-ajax' => array(
                        'admin'
                    ),
                    'eliminar-destinatario-ajax' => array(
                        'admin'
                    )
                ),
                
                Admin\Controller\VehiculoController::class=> [
                    'index' => [
                        'admin'
                    ],
                    'agregar-vehiculo' => [
                        'admin'
                    ],
                    'editar-vehiculo' => [
                        'admin'
                    ],
                    'eliminar-vehiculo' => [
                        'admin'
                    ]
                ],           
  
                Traslado\Controller\TrasladoController::class => array(
                    'index' => [
                        'usuario',
                        'admin'
                    ],
                    'ver-traslados' => [
                        'usuario',
                        'admin'
                    ],
                    'ver-pedidos' => [
                        'usuario',
                        'admin'
                    ],
                    'nuevo-traslado' => [
                        'usuario',
                        'admin'
                    ],
                    'editar-traslado' => [
                        'usuario',
                        'admin'
                    ],
                    'agregar-destinatarios' => [
                        'usuario',
                        'admin'
                    ],
                    'guardar-traslado-ajax' => [
                        'usuario',
                        'admin'
                    ],
                    'obtener-clientes-ajax' => [
                        'usuario',
                        'admin'
                    ],
                    'obtener-almacenes-ajax' => [
                        'usuario',
                        'admin'
                    ],
                    'obtener-conductores-ajax' => [
                        'usuario',
                        'admin'
                    ],
                    'obtener-vehiculos-ajax' => [
                        'usuario',
                        'admin'
                    ],
                    'obtener-destinatarios-ajax' => [
                        'usuario',
                        'admin'
                    ],
                    'guardar-destinatario-ajax' => [
                        'usuario',
                        'admin'
                    ],
                    'eliminar-destinatario-ajax' => [
                        'usuario',
                        'admin'
                    ],
                ),
                
                Reporte\Controller\ReporteController::class => [
                    
                    'index' => [
                        'admin'
                    ],
                    
                    'primer-reporte-pdf' => [
                        'admin'
                    ],
                    
                    'segundo-reporte-pdf' => [
                        'usuario',
                        'admin'
                    ]
                ],
                
                Users\Controller\AuthController::class => [
                    'index' => 'invitado',
                    'salir' => array(
                        'usuario',
                        'admin'
                    )
                ],
                
                Users\Controller\PerfilController::class => [
                    'index' => array(
                        'usuario',
                        'admin'
                    ),
                    'editar-perfil' => array(
                        'usuario',
                        'admin'
                    ),
                    'cambiar-clave' => array(
                        'usuario',
                        'admin'
                    ),
                    'subir-imagen-ajax' => array(
                        'usuario',
                        'admin'
                    )
                ],
                
                Users\Controller\ReestablecerClaveController::class => [
                    'index' => 'invitado',
                    'confirmar-cambio-clave' => 'invitado'
                ],
                
                Users\Controller\RegistroController::class => [
                    'index' => array(
                        'invitado',
                        'usuario',
                        'admin'
                    ),
                    'confirmar-correo' => array(
                        'invitado',
                        'usuario',
                        'admin'
                    )
                ], 
            
            ),
            
            'deny' => array(
                
                'Users\Controller\Auth' => [
                    'index' => array(
                        'usuario',
                        'admin'
                    ),
                    'salir' => 'invitado'
                ],
                
                'Users\Controller\Perfil' => array(
                    'index' => 'invitado',
                    'editar-perfil' => 'invitado',
                    'cambiar-clave' => 'invitado',
                    'subir-imagen' => 'invitado'
                )
            
            )
        )
    )
);