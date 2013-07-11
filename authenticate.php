<?php

define('INCLUDE_CHECK',true);
include ("include.php");



if (  $json->username &&  $json->password  &&  $json->clientToken ) {
    if ( checkUsernamePassword( $json->username, $json->password )) {
	$accessToken = generate_accessToken($json->username);
	saveAccessToken( $accessToken, $json->username );
	saveClientToken( $json->clientToken, $json->username );

    $aProfile = getAvailableProfiles ( $json->username );
    $sProfile = getSelectedProfile( $json->username );

	$responce = array ('clientToken' => $json->clientToken, 'accessToken' => $accessToken, 'availableProfiles' => $aProfile, 'selectedProfile' => $sProfile );
	encode_json($responce);
    }
}else{

    echo "auth failed";
    die;
}

?>