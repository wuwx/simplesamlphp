<?php

/**
 * This page shows a username/password login form, and passes information from it
 * to the sspmod_core_Auth_UserPassBase class, which is a generic class for
 * username/password authentication.
 *
 * @author Olav Morken, UNINETT AS.
 * @package SimpleSAMLphp
 */

if (!array_key_exists('AuthState', $_REQUEST)) {
	throw new SimpleSAML_Error_BadRequest('Missing AuthState parameter.');
}

$globalConfig = SimpleSAML_Configuration::getInstance();
$t = new SimpleSAML_XHTML_Template($globalConfig, 'authYubiKey:yubikeylogin.php');

$errorCode = null;
if (array_key_exists('otp', $_REQUEST)) {
    // attempt to log in
    $errorCode = sspmod_authYubiKey_Auth_Source_YubiKey::handleLogin($authStateId, $_REQUEST['otp']);

    $errorCodes = SimpleSAML\Error\ErrorCodes::getAllErrorCodeMessages();
    $t->data['errorTitle'] = $errorCodes['title'][$errorCode];
    $t->data['errorDesc'] = $errorCodes['desc'][$errorCode];
}

$t->data['errorCode'] = $errorCode;
$t->data['stateParams'] = array('AuthState' => $_REQUEST['authStateId']);
$t->data['logoUrl'] = SimpleSAML\Module::getModuleURL('authYubiKey/resources/logo.jpg');
$t->data['devicepicUrl'] = SimpleSAML\Module::getModuleURL('authYubiKey/resources/yubikey.jpg');
$t->show();
