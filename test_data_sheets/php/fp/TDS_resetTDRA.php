<?php

session_start ();

require_once 'connections.php';

$ID = $_POST['id'];

$query = "SELECT *
          FROM TDRA
          WHERE TDRA_uid = '$ID'";

$result = mysqli_query($conn,$query);

$update = "UPDATE TDRA
           SET TDRA_disp = 'Pending', TDRA_date = NULL
           WHERE TDRA_uid = '$ID'";

mysqli_query($conn,$update);

$tdrm = mysqli_fetch_assoc($result);
$tdrmid = $tdrm['TDRA_sheetID'];

echo $tdrmid;

?>