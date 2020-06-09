<?php
namespace Admin\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Mail\Transport\Smtp;
use Laminas\Mail\Transport\SmtpOptions;
use RuntimeException;


class MailFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        
        $transport = new Smtp();
        
        if (isset($config['mail']['transport']['options']))
        {
            $transport->setOptions(new SmtpOptions($config['mail']['transport']['options']));

        }else{
            throw new RuntimeException(sprintf(
                'No se pudo configurar el correo'
            ));
        }
        
        return $transport;
    }
}