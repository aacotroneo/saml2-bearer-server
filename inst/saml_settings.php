<?php

/**
 * Not everything is used as we are not really an SP.
 * In a perfet world, this attributes are checked against the saml Assertion.
 *    the saml 'audience' has to be equal to 'sp'->entityId
 *    the saml 'issuer' has to be equal to 'idp'->entityID (that's the idp we trust)
 *    the saml 'destination'/'recipient' has to match current URL (/access_token).
 *
 * But I haven't found a way to make such a valid token in PHP (name it: simplesamlphp), but I'm sure there are tools
 * around that can make that.
 *
 * In the meantime, I'm using 'strict' => false, which ignores most of those validations, It just checks signatures.
 * That's good enough for development, but please don't use that in production.
 */
$spBaseUrl = 'http://localhost/oauth'; //or http://<your_domain>

$settingsInfo = array(
    'strict' => false, //this just decodes the saml2-assertion and validates signatures
    'sp' => array(
        'entityId' => $spBaseUrl.'/metadata', //strict: this has to match token 'audience'

        'assertionConsumerService' => array(
            'url' => $spBaseUrl.'/access_token', //strict: this has to match token 'Destinantion'
        ),
        'singleLogoutService' => array(
            'url' => $spBaseUrl.'/sls', //not used
        ),
        'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:unspecified', //use this to retrive the user ID
    ),

    'idp' => array(
        'entityId' => 'http://localhost:8000/simplesaml/saml2/idp/metadata.php', //strict: this has to match Issuer
        'singleSignOnService' => array(
            'url' => 'http://localhost:8000/simplesaml/saml2/idp/SSOService.php',
        ),
        'singleLogoutService' => array(
            'url' => 'http://localhost:8000/simplesaml/saml2/idp/SingleLogoutService.php',
        ),
        //always: this is the certifcate from the IDP!
        'x509cert' => 'MIICgTCCAeoCCQCbOlrWDdX7FTANBgkqhkiG9w0BAQUFADCBhDELMAkGA1UEBhMCTk8xGDAWBgNVBAgTD0FuZHJlYXMgU29sYmVyZzEMMAoGA1UEBxMDRm9vMRAwDgYDVQQKEwdVTklORVRUMRgwFgYDVQQDEw9mZWlkZS5lcmxhbmcubm8xITAfBgkqhkiG9w0BCQEWEmFuZHJlYXNAdW5pbmV0dC5ubzAeFw0wNzA2MTUxMjAxMzVaFw0wNzA4MTQxMjAxMzVaMIGEMQswCQYDVQQGEwJOTzEYMBYGA1UECBMPQW5kcmVhcyBTb2xiZXJnMQwwCgYDVQQHEwNGb28xEDAOBgNVBAoTB1VOSU5FVFQxGDAWBgNVBAMTD2ZlaWRlLmVybGFuZy5ubzEhMB8GCSqGSIb3DQEJARYSYW5kcmVhc0B1bmluZXR0Lm5vMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDivbhR7P516x/S3BqKxupQe0LONoliupiBOesCO3SHbDrl3+q9IbfnfmE04rNuMcPsIxB161TdDpIesLCn7c8aPHISKOtPlAeTZSnb8QAu7aRjZq3+PbrP5uW3TcfCGPtKTytHOge/OlJbo078dVhXQ14d1EDwXJW1rRXuUt4C8QIDAQABMA0GCSqGSIb3DQEBBQUAA4GBACDVfp86HObqY+e8BUoWQ9+VMQx1ASDohBjwOsg2WykUqRXF+dLfcUH9dWR63CtZIKFDbStNomPnQz7nbK+onygwBspVEbnHuUihZq3ZUdmumQqCw4Uvs/1Uvq3orOo/WJVhTyvLgFVK2QarQ4/67OZfHd7R+POBXhophSMv1ZOo',
    ),
);

return $settingsInfo;
