<?php

require_once 'connections.php';

$tdraid = $_POST['tdraid'];
$comment = $_POST['comment'];

$query = "UPDATE TDRA
          SET TDRA_comments = '$comment'
          WHERE TDRA_uid = '$tdraid'";

mysqli_query($conn, $query);

echo $query;

?>