<?php

session_start ();

$date = date( 'y-m-d' );

require_once 'connections.php';

$ID = $_POST['id'];

$query = "SELECT *
          FROM TDRA
          WHERE TDRA_uid = '$ID'";

$approved = mysqli_query($conn,$query);

$update = "UPDATE TDRA
           SET TDRA_disp = 'Rejected', TDRA_date = '$date'
           WHERE TDRA_uid = '$ID'";

mysqli_query($conn,$update);

$tdrm = mysqli_fetch_assoc($approved);
$tdrmid = $tdrm['TDRA_sheetID'];
$findAll = "SELECT *
            FROM TDRA
            WHERE TDRA_sheetID = '$tdrmid'";
$result = mysqli_query($conn,$findAll);

echo $tdrmid;

?>