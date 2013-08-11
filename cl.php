<?php
#!/usr/bin/php
require __DIR__ . '/OPML_Extend.php';
$oOPMLExtend = new OPML_Extend();
$option = getopt("f:");
$file = file_get_contents($option['f']);
$newFile = $oOPMLExtend->setOPML($file)->process();
echo "File generating...\n";
echo "Done: $newFile\n";
