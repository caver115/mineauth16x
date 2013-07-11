<?php

define('INCLUDE_CHECK', true);
include ("include.php");

if ((defined('POST')) && ($json->accessToken && $json->clientToken)) {
    $checkTokenResult = checkTokens($json->accessToken, $json->clientToken);

    if ($checkTokenResult) {
        invalidateAccessToken($json->accessToken, $json->clientToken);
    }
} else {
    echo "auth failed";
    die;
}
?>