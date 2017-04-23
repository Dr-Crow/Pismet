<?php
include_once('CAS.php');
phpCAS::client(SAML_VERSION_1_1,'login.marist.edu',443,'cas'); //SAML required for phpCAS::getAttribute();
phpCAS::setNoCasServerValidation(); //Don't bother checking the SSL cert on the server. DO NOT USE IN PRODUCTION.
phpCAS::forceAuthentication();
phpCAS::logout();

?>