<?php

error_log(print_r($_SERVER, TRUE));

$basepath = '/var/www/minecraft/htdocs';

$userpath = $basepath . $_SERVER['REQUEST_URI'];
$realUserPath = realpath($userpath);

if ($realUserPath === false || strpos($realUserPath, $basepath) !== 0) {
    header("HTTP/1.0 503 Directory Traversal");
    exit;
}

if ($_SERVER['HTTP_IF_NONE_MATCH']) {
    $filemd5 = md5_file($realUserPath);
    if ($filemd5 == $_SERVER['HTTP_IF_NONE_MATCH']) {
        header('HTTP/1.0 304 Not Modified');
        exit;
    }
}

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename=' . basename($realUserPath));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($realUserPath));
ob_clean();
flush();
readfile($realUserPath);
exit;
?>
