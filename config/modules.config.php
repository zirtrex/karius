<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

/**
 * List of enabled modules for this application.
 *
 * This should be an array of module namespaces used in the application.
 */
return [    
    'Laminas\ZendFrameworkBridge',    
    'Laminas\Mail',
    'Laminas\Paginator',
    'Laminas\Mvc\Plugin\Prg',
    'Laminas\Mvc\Plugin\Identity',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Mvc\Plugin\FilePrg',
    'Laminas\Navigation',
    'Laminas\Session',
    'Laminas\I18n',
    'Laminas\Form',
    'Laminas\InputFilter',
    'Laminas\Filter',
    'Laminas\Hydrator',
    'Laminas\Cache',
    'Laminas\Router',
    'Laminas\Validator',
    'Laminas\Db',
    'DOMPDFModule',
    'Admin',
    'Users',
    'Traslado',
    'Reporte'
];
