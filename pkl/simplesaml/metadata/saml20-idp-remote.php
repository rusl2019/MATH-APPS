<?php
$metadata['https://iam.ub.ac.id/auth/realms/ub'] = [
    'entityid' => 'https://iam.ub.ac.id/auth/realms/ub',
    'contacts' => [],
    'metadata-set' => 'saml20-idp-remote',
    'sign.authnrequest' => true,
    'SingleSignOnService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'https://iam.ub.ac.id/auth/realms/ub/protocol/saml',
        ],
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://iam.ub.ac.id/auth/realms/ub/protocol/saml',
        ],
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
            'Location' => 'https://iam.ub.ac.id/auth/realms/ub/protocol/saml',
        ],
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact',
            'Location' => 'https://iam.ub.ac.id/auth/realms/ub/protocol/saml',
        ],
    ],
    'SingleLogoutService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            'Location' => 'https://iam.ub.ac.id/auth/realms/ub/protocol/saml',
        ],
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            'Location' => 'https://iam.ub.ac.id/auth/realms/ub/protocol/saml',
        ],
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact',
            'Location' => 'https://iam.ub.ac.id/auth/realms/ub/protocol/saml',
        ],
    ],
    'ArtifactResolutionService' => [
        [
            'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:SOAP',
            'Location' => 'https://iam.ub.ac.id/auth/realms/ub/protocol/saml/resolve',
            'index' => 0,
        ],
    ],
    'NameIDFormats' => [
        'urn:oasis:names:tc:SAML:2.0:nameid-format:persistent',
        'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
        'urn:oasis:names:tc:SAML:1.1:nameid-format:unspecified',
        'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
    ],
    'keys' => [
        [
            'encryption' => false,
            'signing' => true,
            'type' => 'X509Certificate',
            'X509Certificate' => 'MIICkzCCAXsCBgFvEaGUtzANBgkqhkiG9w0BAQsFADANMQswCQYDVQQDDAJ1YjAeFw0xOTEyMTcwMjExMzlaFw0yOTEyMTcwMjEzMTlaMA0xCzAJBgNVBAMMAnViMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAn79FFtKsIOYvNEDr8Xxmb340wsuSOYdCOD2QXQSPynM22GQptZgSctvIdkB3qL1CUHbXPoXosqT3AC2gKQ3IVjzPc3B+Pg344OTafaIpuXnRa4JZBXgfB8aWD0gA7pSjXIqElpr5E87WCdu8acONCGWmc3E8lz0OJLcnFA6qwq1QvsIuK3TQU9R3GevnGh9v6pUBgW7sU8Xxgr74smDGfV2LRPPhAb/vAkSHfNxotU1Mf3eKUa3E/5ulJ4t2nbJzr+j6X0OxLwln9+Zagm+FGsGeD/FwNx12WZa0BgSHg+59sC4ju1vI0QBmWmb7eDSkRBOUXY5lxEZXD8uDCWa5FQIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQAetaqAUcI23X+rbX5ynKnOXKxqdEwxsoeDQK5laSbCLob5f3HNyArP7OlnPfRGDuEr0WHVMRXyQQrSsqh9uEmxzNegRJE3BXU/4qXrYw1u7LFMVcXsHQDxjWgwwroyN9NjfoZY2qMGUIEtjp/Wz3ayOImiPbVGB8FCRDosBiTpKrNISIarhGZU9ek62y/oGdz9YCD8WFIYzalV0LFSGAUPgR8fnMxTv/UDa4QycnW5CS1iSTceX7QTdtKJxFmzDnmi1aCwXY3K5p90DKgPuVB+EscjWtiks9SplcQRUNyfJyCeTCkPVgi7xOFU8SbpCItQP4YffuoR3giKNHPuvBI5',
        ],
    ],
];
