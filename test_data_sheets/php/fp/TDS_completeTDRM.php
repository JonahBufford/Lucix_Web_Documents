<?php

require_once 'connections.php';

$id = $_POST['id'];

$date = date( 'y-m-d' );

$query = "UPDATE TDRM
          SET TDRM_status = 'Completed', TDRM_dateSigned = '$date'
          WHERE TDRM_uid = '$id'";

mysqli_query($conn,$query);

echo $id;

?>