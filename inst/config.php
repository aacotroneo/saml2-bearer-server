<?php

return array(
    'templates.path' => __DIR__ . "/../src/Oauth2/View",
    'view' => new Slim\Views\Twig(),

    'bd' => array(
        'dsn' => 'pgsql:host=localhost;port=5432;dbname=oauth',
        'username' => 'postgres',
        'password' => 'postgres'
    ),

    'saml' => array(
        'settings_file' => __DIR__ . '/saml_settings.php'
    ),

    'log' => array(
        'dir' => __DIR__ . '/logs'
    ),

);