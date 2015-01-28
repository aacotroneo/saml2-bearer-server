<?php

/**
 * Not everything is used as we are not really an SP.
 * In a perfet world, this attributes are checked against the saml Assertion.
 *    the saml 'audience' has to be equal to 'sp'->entityId
 *    the saml 'issuer' has to be equal to 'idp'->entityID (that's the idp we trust)
 *    the saml 'destination'/'recipient' has to match current URL (/access_token)
 *
 * But I haven't found a way to make such a valid token in PHP (name it: simplesamlphp), but I'm sure there are tools
 * around that can make that.
 *
 * In the meantime, I'm using 'strict' => false, which ignores most of those validations, It just checks signatures!
 * Providing that your clients are of trust and make back-channel requests with their credentials, It may be acceptable,
 * but be careful anyway!!
 *
 */
$spBaseUrl = 'http://localhost/oauth'; //or http://<your_domain>

$settingsInfo = array (
    'strict' => false, //this just decodes the saml2-assertion and validates signatures
    'sp' => array (
        'entityId' => $spBaseUrl.'/metadata', //strict: this has to match token 'audience'

        'assertionConsumerService' => array (
            'url' => $spBaseUrl.'/access_token', //strict: this has to match token 'Destinantion'
        ),
        'singleLogoutService' => array (
            'url' => $spBaseUrl.'/sls', //not used
        ),
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:unspecified', //use this to retrive the user ID
    ),

    'idp' => array (
        'entityId' => 'http://localhost:8000/simplesaml/saml2/idp/metadata.php', //strict: this has to match Issuer
        'singleSignOnService' => array (
            'url' => 'http://localhost:8000/simplesaml/saml2/idp/SSOService.php',
        ),
        'singleLogoutService' => array (
            'url' => 'http://localhost:8000/simplesaml/saml2/idp/SingleLogoutService.php',
        ),

        //always: this is the certifcate from the IDP!
        'x509cert' => 'MIID/TCCAuWgAwIBAgIJAI4R3WyjjmB1MA0GCSqGSIb3DQEBCwUAMIGUMQswCQYDVQQGEwJBUjEVMBMGA1UECAwMQnVlbm9zIEFpcmVzMRUwEwYDVQQHDAxCdWVub3MgQWlyZXMxDDAKBgNVBAoMA1NJVTERMA8GA1UECwwIU2lzdGVtYXMxFDASBgNVBAMMC09yZy5TaXUuQ29tMSAwHgYJKoZIhvcNAQkBFhFhZG1pbmlAc2l1LmVkdS5hcjAeFw0xNDEyMDExNDM2MjVaFw0yNDExMzAxNDM2MjVaMIGUMQswCQYDVQQGEwJBUjEVMBMGA1UECAwMQnVlbm9zIEFpcmVzMRUwEwYDVQQHDAxCdWVub3MgQWlyZXMxDDAKBgNVBAoMA1NJVTERMA8GA1UECwwIU2lzdGVtYXMxFDASBgNVBAMMC09yZy5TaXUuQ29tMSAwHgYJKoZIhvcNAQkBFhFhZG1pbmlAc2l1LmVkdS5hcjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAMbzW/EpEv+qqZzfT1Buwjg9nnNNVrxkCfuR9fQiQw2tSouS5X37W5h7RmchRt54wsm046PDKtbSz1NpZT2GkmHN37yALW2lY7MyVUC7itv9vDAUsFr0EfKIdCKgxCKjrzkZ5ImbNvjxf7eA77PPGJnQ/UwXY7W+cvLkirp0K5uWpDk+nac5W0JXOCFR1BpPUJRbz2jFIEHyChRt7nsJZH6ejzNqK9lABEC76htNy1Ll/D3tUoPaqo8VlKW3N3MZE0DB9O7g65DmZIIlFqkaMH3ALd8adodJtOvqfDU/A6SxuwMfwDYPjoucykGDu1etRZ7dF2gd+W+1Pn7yizPT1q8CAwEAAaNQME4wHQYDVR0OBBYEFPsn8tUHN8XXf23ig5Qro3beP8BuMB8GA1UdIwQYMBaAFPsn8tUHN8XXf23ig5Qro3beP8BuMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQELBQADggEBAGu60odWFiK+DkQekozGnlpNBQz5lQ/bwmOWdktnQj6HYXu43e7sh9oZWArLYHEOyMUekKQAxOK51vbTHzzw66BZU91/nqvaOBfkJyZKGfluHbD0/hfOl/D5kONqI9kyTu4wkLQcYGyuIi75CJs15uA03FSuULQdY/Liv+czS/XYDyvtSLnu43VuAQWN321PQNhuGueIaLJANb2C5qq5ilTBUw6PxY9Z+vtMjAjTJGKEkE/tQs7CvzLPKXX3KTD9lIILmX5yUC3dLgjVKi1KGDqNApYGOMtjr5eoxPQrqDBmyx3flcy0dQTdLXud3UjWVW3N0PYgJtw5yBsS74QTGD4=',
    ),
);

return $settingsInfo;
