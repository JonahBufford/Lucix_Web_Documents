<?php

// show header data, log-in info
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

date_default_timezone_set('America/Los_Angeles');


// create header and banner html for instertion into pages
if (isset ( $_SESSION ['username'] )) {
	
	$user = $_SESSION ['username'] . "  <a href='APR_authenticate.php'>log-out</a>";
	$perm = $_SESSION ['oltpPerm'];
	$userID = $_SESSION ['userID'];
	$loginButtonVerify = "";
	
} else {
	
	$user = "<a href='APR_authenticate.php'>log-in</a>";
	$loginButtonVerify = "hidden";
}

$headerString = "<a href='home.php'>Lucix Enterprise Software</a>|&nbsp&nbsp&nbsp&nbsp" . $title;

$header = "
<div id='header_container' class='printHide'>
    <div id='header'>
		<table class='headerTable'>
			<tr>
				<td class='cell1'>
					$headerString
				</td>
				<td class='cell2'>
					$user
				</td>
			</tr>
		</table>
	</div>
</div>";

?>
