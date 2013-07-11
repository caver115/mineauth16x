<?php

define('INCLUDE_CHECK',true);
include ("include.php");


if (  $json->accessToken &&  $json->clientToken ) {
	$checkTokenResult = checkTokens( $json->accessToken, $json->clientToken );

    if ( $checkTokenResult ) {

#	$accessToken = generate_accessToken( $checkTokenResult['mail'] );
#	saveAccessToken( $accessToken, $checkTokenResult['mail'] );

    $aProfile = getAvailableProfiles ( $checkTokenResult );
    $sProfile = getSelectedProfile( $checkTokenResult );


	$responce = array ('clientToken' => $json->clientToken, 'accessToken' => $json->accessToken, 'selectedProfile' => $sProfile );
	encode_json($responce);
    }
}else{
    echo "auth failed";
    die;
}


?>