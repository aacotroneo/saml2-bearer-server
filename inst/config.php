<?php

return array(
    'templates.path' => __DIR__."/../src/Oauth2/View",
    'view' => new Slim\Views\Twig(),

    'db' => array(
        'dsn' => 'pgsql:host=localhost;port=5432;dbname=oauth2_server_php',
        'username' => 'postgres',
        'password' => 'postgres',
    ),

    'saml' => array(
        'settings_file' => __DIR__.'/saml_settings.php',
    ),

    'log' => array(
        'dir' => __DIR__.'/logs',
    ),

);
