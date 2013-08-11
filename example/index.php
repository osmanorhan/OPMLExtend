<?php
require '../OPML_Extend.php';
$oOPMLExtend = new OPML_Extend();
$opml = file_get_contents('sample.xml');
$newFile = $oOPMLExtend->setOPML($opml)->process();
?>
<a href="<?php echo $newFile; ?>">Your new subscriptions</a>
