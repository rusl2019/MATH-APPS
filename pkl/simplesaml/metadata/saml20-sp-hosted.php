<?php
$metadata[SIMPLESAML_APP_ID] = array (
  'SingleLogoutService' => 
  array (
    0 => 
    array (
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => SIMPLESAML_BASE_URL.'/module.php/saml/sp/saml2-logout.php/'.SIMPLESAML_APP_ID,
    ),
  ),
  'AssertionConsumerService' => 
  array (
    0 => 
    array (
      'index' => 0,
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
      'Location' => SIMPLESAML_BASE_URL.'/module.php/saml/sp/saml2-acs.php/'.SIMPLESAML_APP_ID,
    ),
    1 => 
    array (
      'index' => 1,
      'Binding' => 'urn:oasis:names:tc:SAML:1.0:profiles:browser-post',
      'Location' => SIMPLESAML_BASE_URL.'/module.php/saml/sp/saml1-acs.php/'.SIMPLESAML_APP_ID,
    ),
    2 => 
    array (
      'index' => 2,
      'Binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Artifact',
      'Location' => SIMPLESAML_BASE_URL.'/module.php/saml/sp/saml2-acs.php/'.SIMPLESAML_APP_ID,
    ),
    3 => 
    array (
      'index' => 3,
      'Binding' => 'urn:oasis:names:tc:SAML:1.0:profiles:artifact-01',
      'Location' => SIMPLESAML_BASE_URL.'/module.php/saml/sp/saml1-acs.php/'.SIMPLESAML_APP_ID.'/artifact',
    ),
  ),
  'contacts' => 
  array (
    0 => 
    array (
      'emailAddress' => 'netadmin@ub.ac.id',
      'contactType' => 'technical',
      'givenName' => 'Administrator',
    ),
  ),
  'certData' => SIMPLESAML_CERT_DATA,
);