<?php
namespace Traslado;

use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Admin\Model\AlmacenTable;
use Admin\Model\ClienteTable;
use Admin\Model\ConductorTable;
use Admin\Model\DestinatarioTable;
use Admin\Model\TrasladoTable;
use Admin\Model\UsuarioTable;
use Admin\Model\VehiculoTable;


class Module implements ConfigProviderInterface
{

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
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

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\TrasladoController::class => function ($container) {
                    return new Controller\TrasladoController(
                        $container,
                        $container->get(AlmacenTable::class),
                        $container->get(ClienteTable::class),
                        $container->get(ConductorTable::class),
                        $container->get(DestinatarioTable::class),
                        $container->get(TrasladoTable::class),
                        $container->get(UsuarioTable::class),
                        $container->get(VehiculoTable::class)
                     );
                }
            ]
        ];
    }
}
















