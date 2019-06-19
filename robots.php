<?php

require_once(dirname(__FILE__)."/include/init.php");

$request = new LocalObject($_GET);
header("Content-Type: text/plain");
$currentDomain = GetCurrentSubdomain();
if($currentDomain)
	readfile(PROJECT_DIR."website/".WEBSITE_FOLDER."/".$currentDomain.".robots.txt");
else
	readfile(PROJECT_DIR."website/".WEBSITE_FOLDER."/robots.txt");
?>