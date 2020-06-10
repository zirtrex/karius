<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [
    'db' => array(
		'driver' 	=> 'Pdo',
    	'dsn' 		=> 'mysql:dbname=karius_db; host=localhost',
    	'driver_options' => array(
    		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
    	)
	),

    'session_validators' => [
        \Laminas\Session\Validator\RemoteAddr::class,
        \Laminas\Session\Validator\HttpUserAgent::class,
    ],

    'session_config' => [
        'remember_me_seconds' => 604800,
        'use_cookies' => true,
        'cookie_lifetime' => 604800,
        'name' => 'karius_session',
    ],

    'session_storage' => [
        'type' => \Laminas\Session\Storage\SessionArrayStorage::class,
    ],

    
];
