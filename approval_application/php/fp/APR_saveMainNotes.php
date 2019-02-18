<?php

require_once 'connections.php';

$notes = $_POST['notes'];
$id = $_POST['id'];

$query = "UPDATE APR_main
          SET APR_notes = '$notes'
          WHERE APR_uid = '$id'";

mysqli_query($conn,$query);

echo 1;

?>