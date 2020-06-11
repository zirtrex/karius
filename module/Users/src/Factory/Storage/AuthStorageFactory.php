<?php

namespace Users\Factory\Storage;
 
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Laminas\Db\Adapter\AdapterInterface;
use Users\Storage\AuthStorage;

class AuthStorageFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {             
        $dbAdapter = $container->get(AdapterInterface::class);
        
        $storage = new AuthStorage();
        
        $storage->setDbHandler($dbAdapter);
         
        return $storage;
    }
}









