<?php

if (isset ( $_GET ['file'] )){
	
	$filename = $_GET ['file'];
	
	header("Content-type: application/pdf");
	
	readfile($filename);
}

?>