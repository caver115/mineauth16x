<?php

define('INCLUDE_CHECK',true);
include ("include.php");

if (  $json->accessToken &&  $json->clientToken ) {
	$checkTokenResult = checkTokens( $json->accessToken, $json->clientToken );

    if ( $checkTokenResult ) {
	invalidateAccessToken ( $json->accessToken, $json->clientToken );
    }
}
?>