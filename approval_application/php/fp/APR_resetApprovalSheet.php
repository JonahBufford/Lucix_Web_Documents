<?php

require_once 'connections.php';

$id = $_POST['id'];

$query = "UPDATE APR_main
            SET APR_status = 'Pending', APR_dateSigned = NULL
            WHERE APR_uid = '$id'";

mysqli_query($conn,$query);

echo 1;

?>