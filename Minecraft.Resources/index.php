<?php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
$directory = dirname ( __FILE__ );

    function directoryToArray($directory) {
        $arrayItems = array();
        $handle = opendir($directory);
    	static $relpath = array("");
		static $currpath ="";
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
            preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md|php))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
			
            if (!$skip) {
                if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
                   
						array_push($relpath, $file . DIRECTORY_SEPARATOR);
						$currpath = "";
						foreach($relpath as $c){
						$currpath .= $c;
						}
                        $arrayItems = array_merge($arrayItems, directoryToArray($directory. DIRECTORY_SEPARATOR . $file));
                    
                } else {
                        $file = $currpath . $file;
                        $arrayItems[] = $file;
							//print($file . " <br> ");                  
                }
            }
        }
		array_pop($relpath);
		$currpath = "";
		foreach($relpath as $c){
			$currpath .= $c;
						}
        closedir($handle);
        }
        return $arrayItems;
    }
	$scanned_directory = directoryToArray($directory);

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
    $contents->addChild('ETag', md5_file($directory . DIRECTORY_SEPARATOR .$file));
    $contents->addChild('Size', filesize($directory . DIRECTORY_SEPARATOR . $file));
    $contents->addChild('StorageClass', 'STANDARD');
}

    Header('Content-type: text/xml');
    print($xml->asXML());
?>
