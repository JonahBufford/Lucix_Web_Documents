<?php

require_once 'connections.php';

$id = $_POST['id'];

$approveQ = "UPDATE APR_app
             SET APR_app_dateSigned = NULL
             WHERE APR_app_uid = '$id'";

mysqli_query($conn,$approveQ);

echo 1;

?>