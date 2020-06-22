<?php

require_once "parceClass.php";

$sampler = new channelSampling();

if ($sampler->readFile("output.vinteo")) {
	$sampler->convProcess();
	
	// $object = $sampler->getall();
	// $object = $sampler->getLine(0);
	$object = $sampler->getParameter(0,'Duration');

	echo '<pre>'.print_r($object, true).'</pre>';
} else {
	die("File not found or can't be read");
}



?>