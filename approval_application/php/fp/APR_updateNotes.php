<?php

require_once 'connections.php';

$id = $_POST['id'];
$notes = $_POST['notes'];

$query = "UPDATE APR_app
          SET APR_app_notes = '$notes'
          WHERE APR_app_uid = '$id'";

mysqli_query($conn,$query);

echo 1;

?>