<?php

require_once 'connections.php';

$id = $_POST['id'];

$date = date( "Y-m-d" );

$query = "UPDATE APR_main
          SET APR_dateSigned = '$date', APR_status = 'Approved'
          WHERE APR_uid = '$id'";

mysqli_query($conn, $query);

echo 1;

?>