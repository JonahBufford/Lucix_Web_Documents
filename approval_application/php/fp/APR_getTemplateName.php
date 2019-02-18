<?php

require_once 'connections.php';

$id = $_POST['id'];

$query = "SELECT APR_tempName
          FROM APR_main
          WHERE APR_uid = '$id'";

$result = mysqli_query($conn,$query);

$i = mysqli_fetch_assoc($result);

$name = $i['APR_tempName'];

echo $name;

?>