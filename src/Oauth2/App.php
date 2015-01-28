<?php

namespace Aac\OAuth2;

use Aac\Oauth2\Endpoint\AuthorizeEndpoint;
use Aac\Oauth2\Endpoint\TokenEndpoint;
use Aac\Oauth2\Endpoint\TokenInfoEndpoint;
use Aac\Oauth2\Http\RequestAdapter;
use Aac\Oauth2\Http\ResponseAdapter;

use OAuth2\Scope;
use OAuth2\Storage\Memory;
use Slim\Slim;

class App {


    function __construct($config)
    {
        $app = new Slim($config);
        $this->configure($app);

        $authenticate = function () {
            return function () {
//                implement this if you want to add userId to the token during the authorization code grant
//                if (!$login->isAuthenticated()) {
//                    $login->login(); //this just redirects
//                }
            };
        };

        $app->map('/authorize', $authenticate(), function () use ($app) {
            $ep = new AuthorizeEndpoint($app);
            $ep->run();
        })->via('GET', 'POST');


        $app->post('/token', function () use ($app) {
            $ep = new TokenEndpoint($app);
            $ep->run();
        });

        $app->get('/tokeninfo', function () use ($app) {
            //helper to validate tokens
            $ep = new TokenInfoEndpoint($app);
            $ep->run();
        });


    }

    protected function configure(Slim $app)
    {

        $app->container->singleton('request', function ($c) {
            //Use adapter so slim and oauth2 library works with the same object
            return new RequestAdapter($c['environment']); //Request::createFromGlobals();
        });

        $app->container->singleton('response', function ($c) {
            //Use adapter so slim and oauth2 library works with the same object
            return new ResponseAdapter();
        });

        $app->container->singleton('saml_settings', function ($c) {
            if ($saml_settings = include($c['settings']['saml']['settings_file'])) {
                return $saml_settings;
            } else {
                die("couldn find settings file in ['settings']['saml']['settings_file'] ");
            }

        });

        $app->container->singleton('oauthServer', function ($c) {
            //basic set up
            $settings =  $c['settings'];

            $storage = new  \OAuth2\Storage\Pdo($settings['bd']);

            $server = new \OAuth2\Server($storage);


            //saml-bearer grant! This conf is actually the file from /inst/saml_settings.php
            //and its almost directly handled by onelogin/php-saml library
            //refer to onelogin/php-saml for more information.
            //Note that you will have to properly configure saml IDP
            $server->addGrantType(new \OAuth2\GrantType\Saml2Bearer($c['saml_settings']));


            //just in case you only want to see how to set up basic stuff using slim
            $server->addGrantType(new \OAuth2\GrantType\ClientCredentials($storage));
            $server->addGrantType(new \OAuth2\GrantType\AuthorizationCode($storage));


            $defaultScope = 'basic';
            $supportedScopes = array(
                'basic',
                'mail',
                'bank_account'
            );

            $memory = new Memory(array(
                'default_scope' => $defaultScope,
                'supported_scopes' => $supportedScopes
            ));
            $scopeUtil = new Scope($memory);
            $server->setScopeUtil($scopeUtil);


            return $server;

        });


    }

} 