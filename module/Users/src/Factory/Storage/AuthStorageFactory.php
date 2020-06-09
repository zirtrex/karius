<?php

namespace Users\Factory\Storage;
 
use Laminas\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;
use Users\Storage\AuthStorage;
 

class AuthStorageFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $storage = new AuthStorage('karius_session');
         
        return $storage;
    }
}









