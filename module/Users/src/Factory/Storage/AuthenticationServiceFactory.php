<?php

namespace Users\Factory\Storage;
 
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Authentication\Adapter\DbTable\CredentialTreatmentAdapter;
use Interop\Container\ContainerInterface;
use Users\Storage\AuthStorage;


class AuthenticationServiceFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter = $container->get(AdapterInterface::class);
        
        $dbTableAuthAdapter = new CredentialTreatmentAdapter($dbAdapter, 'ks_usuario', 'usuario', 'clave');
        
        //$select = $dbTableAuthAdapter->getDbSelect();
        //$select->where('estado = "1"');
         
        $authService = new AuthenticationService($container->get(AuthStorage::class), $dbTableAuthAdapter);
         
        return $authService;
    }
}









