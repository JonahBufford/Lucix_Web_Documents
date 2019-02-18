<?php

require_once 'connections.php';

$id = $_POST['id'];

$query = "SELECT APR_isTemplate
          FROM APR_main
          WHERE APR_uid = '$id'";

$result = mysqli_query($conn,$query);

$i = mysqli_fetch_assoc($result);

if($i['APR_isTemplate'] == 0){
    echo 0;
}

else{
    echo 1;
}

?>