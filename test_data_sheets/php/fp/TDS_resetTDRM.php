<?php

require_once 'connections.php';

$ID = $_POST['id'];

$update = "UPDATE TDRM
           SET TDRM_status = 'Active', TDRM_dateSigned = NULL
           WHERE TDRM_uid = '$ID'";

mysqli_query($conn,$update);

echo $ID;

?>