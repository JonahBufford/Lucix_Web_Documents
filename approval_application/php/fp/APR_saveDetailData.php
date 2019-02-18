<?php

require_once 'connections.php';

$id = $_POST['id'];
$data = $_POST['val'];

$query = "UPDATE APR_details
            SET APR_details_data = '$data'
            WHERE APR_details_uid = '$id'";

mysqli_query($conn,$query);

echo 1;

?>