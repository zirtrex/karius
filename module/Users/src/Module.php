<?php
namespace Users;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\ModuleManager\Feature\ConfigProviderInterface;
use Laminas\Mail\Transport\Smtp;
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
                Controller\RegistroController::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\RegistroController(
                        $container->get(Model\UsuarioTable::class),
                        $dbAdapter,
                        $container->get(\Laminas\Mail\Transport\Smtp::class)
                    );
                },
                Controller\PerfilController::class => function ($container) {
                    return new Controller\PerfilController(
                        $container,
                        $container->get(Model\UsuarioTable::class)
                    );
                },
                Controller\ReestablecerClaveController::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Controller\ReestablecerClaveController(
                        $container,
                        $dbAdapter,
                        $container->get(Model\UsuarioTable::class),
                        $container->get(\Laminas\Mail\Transport\Smtp::class)
                    );
                }
            ]
        ];
    }
}
















