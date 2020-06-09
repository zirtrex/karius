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
  
                Traslado\Controller\TrasladoController::class => array(
                    'index' => array(
                        'usuario'
                    ),
                    'ver-traslados' => array(
                        'usuario'
                    ),
                    'ver-pedidos' => array(
                        'usuario'
                    ),                    
                    'nuevo-traslado' => array(
                        'usuario'
                    ),
                    'editar-traslado' => array(
                        'usuario'
                    ),
                    'agregar-destinatarios' => array(
                        'usuario'
                    ),
                    'guardar-traslado-ajax' => array(
                        'usuario'
                    ),
                    'obtener-clientes-ajax' => array(
                        'usuario'
                    ),
                    'obtener-almacenes-ajax' => array(
                        'usuario'
                    ),
                    'obtener-conductores-ajax' => array(
                        'usuario'
                    ),
                    'obtener-vehiculos-ajax' => array(
                        'usuario'
                    ),
                    'obtener-destinatarios-ajax' => array(
                        'usuario'
                    ),
                    'guardar-destinatario-ajax' => array(
                        'usuario'
                    ),
                    'eliminar-destinatario-ajax' => array(
                        'usuario'
                    )
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
                ]
            
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