<?php

$config = [
    'admin' => [
        'core:AdminPassword',
    ],
    SIMPLESAML_APP_ID => [
        'saml:SP',
        'entityID' => SIMPLESAML_APP_ID,
        'privatekey' => 'ubauth.pem',
        'certificate' => 'ubauth.crt',
        'idp' => 'https://iam.ub.ac.id/auth/realms/ub',
    ],
];
