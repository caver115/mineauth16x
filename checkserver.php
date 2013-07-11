<?php

define('INCLUDE_CHECK', true);
include ("include.php");

if (checkServer($_GET["user"], $_GET["serverId"])) {
    echo "YES";
} else {
    echo "NO";
}
?>
