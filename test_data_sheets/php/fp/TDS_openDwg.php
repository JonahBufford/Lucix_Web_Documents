<?php

if (isset ( $_GET ['dwg'] )){
	
	$filename = $_GET ['dwg'];
	
	header("Content-type: application/pdf");
	
	readfile($filename);
}

?>