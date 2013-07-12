<?php

$directory = '/var/www/minecraft/htdocs/Minecraft.Resources';
$scanned_directory = array_diff(scandir($directory), array('..', '.', 'index.php', 'i2.php'));


$xmlstr = <<< XML
<ListBucketResult xmlns="http://s3.amazonaws.com/doc/2006-03-01/">
<Name>Minecraft.Resources</Name>
<Prefix/>
<Marker/>
<MaxKeys>1000</MaxKeys>
<IsTruncated>false</IsTruncated>
</ListBucketResult>
XML;



$xml = new SimpleXMLElement($xmlstr);


foreach ($scanned_directory as $file) {
    $contents = $xml->addChild('Contents');
    $contents->addChild('Key', $file);
    $contents->addChild('LastModified', '2013-04-30T09:25:54.000Z');
    $contents->addChild('ETag', md5_file($file));
    $contents->addChild('Size', filesize($file));
    $contents->addChild('StorageClass', 'STANDARD');
}

Header('Content-type: text/xml');
print($xml->asXML());
