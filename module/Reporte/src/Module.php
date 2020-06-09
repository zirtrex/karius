<?php
namespace Reporte;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Admin\Model;


class Module implements ConfigProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\ReporteController::class => function ($container) {
                    return new Controller\ReporteController(
                        $container,
                        $container->get(Model\AlmacenTable::class),
                        $container->get(Model\ClienteTable::class),
                        $container->get(Model\ConductorTable::class),
                        $container->get(Model\DestinatarioTable::class),
                        $container->get(Model\TrasladoTable::class),
                        $container->get(Model\UsuarioTable::class),
                        $container->get(Model\VehiculoTable::class)
                    );
                }
            ]
        ];
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }
}
















