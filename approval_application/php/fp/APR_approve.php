<?php

require_once 'connections.php';

$id = $_POST['id'];

$date = date("Y-m-d");

$checkQ = "SELECT APR_app_isOrdered, APR_app_order, APR_app_sheetID
           FROM APR_app
           WHERE APR_app_uid = '$id'";

$checkR = mysqli_query($conn,$checkQ);

$check = mysqli_fetch_assoc($checkR);

$isOrdered = $check['APR_app_isOrdered'];
$order = $check['APR_app_order'];
$sheetId = $check['APR_app_sheetID'];

if($isOrdered){
    $query = "SELECT *
              FROM APR_app
              WHERE APR_app_sheetID = '$sheetId' AND APR_app_order < '$order' AND APR_app_order != 0 AND APR_app_dateSigned = '0000-00-00'";

    $result = mysqli_query($conn,$query);

    if(mysqli_num_rows($result) > 0){
    }

    else{
        $approveQ = "UPDATE APR_app
                     SET APR_app_dateSigned = '$date'
                     WHERE APR_app_uid = '$id'";

        mysqli_query($conn,$approveQ);
    }
}

else{
    $approveQ = "UPDATE APR_app
                 SET APR_app_dateSigned = '$date'
                 WHERE APR_app_uid = '$id'";

    mysqli_query($conn,$approveQ);
}
if($isOrdered){
    echo $order;
}
else{
    echo -1;
}

?>