<?php
namespace Reporte;

use Laminas\Router\Http\Segment;


return array(
    'router' => array(
        'routes' => array(
            
            'reporte' => array(
                'type' => Segment::class,
                'options' => array(
                    'route' => '/admin/reporte[/:action][/imprimirpdf/:imprimirpdf][/:codusuario][/:semestre][/:codescuela][/:codcurso]',
                    'constraints' => array(
                        'action'            => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'imprimirpdf'       => 'si',
                        'codusuario'        => '[0-9]*',
                        'semestre'          => '[a-zA-Z0-9_-]*',
                        'codescuela'        => '[0-9]+',
                        'codcurso'          => '[0-9]*',
                        'docente'           => '[a-zA-Z]'
                    ),
                    'defaults' => array(
                        'controller' => Controller\ReporteController::class,
                    )
                )
            )
        
        )
    ),
    
    'service_manager' => array(
    ),
    
    'controllers' => array(
        'factories' => array(
            //Controller\ReporteController::class => InvokableFactory::class
        )
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            'reporte' => __DIR__ . '/../view'
        )
    )
);
