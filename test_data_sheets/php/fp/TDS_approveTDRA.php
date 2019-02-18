<?php

session_start ();

require_once 'connections.php';

$date = date( 'y-m-d' );

$ID = $_POST['id'];

$query = "SELECT *
          FROM TDRA
          WHERE TDRA_uid = '$ID'";

$approved = mysqli_query($conn,$query);

$update = "UPDATE TDRA
           SET TDRA_disp = 'Approved', TDRA_date = '$date'
           WHERE TDRA_uid = '$ID'";

mysqli_query($conn,$update);

$tdrm = mysqli_fetch_assoc($approved);
$tdrmid = $tdrm['TDRA_sheetID'];

echo $tdrmid;

?>