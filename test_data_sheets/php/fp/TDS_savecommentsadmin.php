<?php

require_once 'connections.php';

$tdraid = $_POST['tdraid'];
$comment = $_POST['comment'];
$role = $_POST['role'];
$name = $_POST['name'];

$query = "UPDATE TDRA
          SET TDRA_comments = '$comment', TDRA_role = '$role', TDRA_assign = '$name'
          WHERE TDRA_uid = '$tdraid'";

mysqli_query($conn, $query);

echo $query;

?>