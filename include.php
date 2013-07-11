<?php

define('DEBUG', TRUE);

if (!defined('INCLUDE_CHECK'))
    die("You don't have permissions to run this");



$db_host = 'localhost'; // Ip-адрес MySQL
$db_port = '3306'; // Порт базы данных
$db_user = 'root'; // Пользователь базы данных
$db_pass = ''; // Пароль базы данных
$db_database = 'minecraft161'; //База данных

$link = @mysql_connect($db_host . ':' . $db_port, $db_user, $db_pass) or die(mysql_error());

mysql_select_db($db_database, $link);
mysql_query("SET names UTF8");


if (($_SERVER['REQUEST_METHOD'] == 'POST' ) && (stripos($_SERVER["CONTENT_TYPE"], "application/json") === 0)) {
    $json = json_decode($HTTP_RAW_POST_DATA);
    define('POST', TRUE);
} else {
    if (($_SERVER['REQUEST_METHOD'] != "GET")) {
        echo "Bad request";
        die;
    } else {
        define('GET', TRUE);
    }
}

function encode_json($r) {
    $j = json_encode($r);
    echo $j;
#    error_log(print_r($j,TRUE));
}

function checkUsernamePassword($u, $p) {
    $u = mysql_real_escape_string($u);
    $p = md5(md5(mysql_real_escape_string($p)));
    $dbResult = mysql_query(" select * from users where lower(mail) = lower('$u') and password='$p' ");
    if (mysql_num_rows($dbResult) == 1)
        return true;
    else
        return false;
}

function checkTokens($at, $ct) {
    $at = mysql_real_escape_string($at);
    $ct = mysql_real_escape_string($ct);
    $dbResult = mysql_query("select mail from users where accessToken = '$at' and clientToken='$ct' ");

    if (mysql_num_rows($dbResult) == 1) {
        $row = mysql_fetch_assoc($dbResult);
        return $row['mail'];
    } else {
        return false;
    }
}

function generate_accessToken($u) {
    return md5(md5($u) . mt_rand(100000, 900000) . microtime());
}

function saveAccessToken($t, $u) {
    $t = mysql_real_escape_string($t);
    $u = mysql_real_escape_string($u);
    $dbResult = mysql_query("update users set accessToken = '$t' where lower(mail) = lower('$u') ");
}

function saveClientToken($t, $u) {
    $t = mysql_real_escape_string($t);
    $u = mysql_real_escape_string($u);
    $dbResult = mysql_query("update users set clientToken = '$t' where lower(mail) = lower('$u') ");
}

function invalidateAccessToken($at, $ct) {
    $at = mysql_real_escape_string($at);
    $ct = mysql_real_escape_string($ct);
    $dbResult = mysql_query("update users  set accessToken = '' where clientToken='$ct' ");
}

function getAvailableProfiles($u) {
    $u = mysql_real_escape_string($u);
    $dbResult = mysql_query("select id, nickname as name from profiles where lower(mail) = lower('$u')  ");

    if (mysql_num_rows($dbResult) == 0) {
        createDefaultProfile($u);
        $dbResult = mysql_query("select id, nickname as name from profiles where lower(mail) = lower('$u')  ");
    }
    $i = 0;
    while ($row = mysql_fetch_assoc($dbResult)) {
        $profile[$i] = $row;
        $i++;
    }
    return $profile;
}

function getSelectedProfile($u) {
    $u = mysql_real_escape_string($u);
    $dbResult = mysql_query("select u.selectedprofile as id ,p.nickname as name from users u, profiles p where ( lower(u.mail) = lower('$u')) and ( u.selectedprofile = p.id )");
    $row = mysql_fetch_assoc($dbResult);
    return $row;
}

function createDefaultProfile($u) {
    $u = mysql_real_escape_string($u);
    $nickname = explode("@", $u);
    $dbResult = mysql_query("insert into profiles ( nickname, mail ) values ( '$nickname[0]', '$u' )");
    $id = mysql_insert_id();
    $dbResult = mysql_query("update users set selectedprofile = $id where lower(mail) = lower('$u') ");
}

function joinServer($u, $t, $s) {
    $u = mysql_real_escape_string($u);
    $t = mysql_real_escape_string($t);
    $s = mysql_real_escape_string($s);
    $token = explode(":", $t);
    
    $q = "update users u join profiles p on u.mail = p.mail set u.serverId = '$s' where ((u.accessToken = '$token[1]') and (p.nickname = '$u'))";
    $dbResult = mysql_query($q);
        
    if (mysql_affected_rows() == 1) {
        return TRUE;
    } else {
        return FALSE;
    }
}

?>
