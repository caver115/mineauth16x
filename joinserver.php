<?php

define('INCLUDE_CHECK', true);
include ("include.php");

if (joinServer($_GET["user"], $_GET["sessionId"], $_GET["serverId"])) {
    echo "OK";
} else {
    echo "ERROR: join error";
}

?>
