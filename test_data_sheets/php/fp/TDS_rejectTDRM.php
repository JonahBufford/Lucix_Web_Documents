<?php

require_once 'connections.php';

$id = $_POST['id'];

$date = date( 'y-m-d' );

$query = "UPDATE TDRM
          SET TDRM_status = 'Rejected', TDRM_date = '$date'
          WHERE TDRM_uid = '$id'";

mysqli_query($conn,$query);

echo $id;

?>