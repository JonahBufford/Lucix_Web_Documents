<?php

require_once 'connections.php';

$id = $_POST['id'];
$assignments = $_POST['assignments'];
$notifications = $_POST['notifications'];
$isOrdered = $_POST['isOrdered'];

$deleteQuery = "DELETE FROM APR_app
                WHERE APR_app_sheetID = '$id'";

mysqli_query($conn,$deleteQuery);

if(isset($_POST['assignments'])){
    $assignments = $_POST['assignments'];
    
    foreach($assignments as $li){
        $appName = $li[0];
        $appNot1 = $li[1];
        $appNotC = $li[2];
        
        if($isOrdered == 0){
            $appOrder = "";
        }
        
        else{
            $appOrder = $li[3];
        }

        $appQuery = "INSERT INTO APR_app (APR_app_sheetID, APR_app_isTemplate, APR_app_isApprover, APR_app_notifEnd, APR_app_notifBeginning, 
                                            APR_app_order, APR_app_name, APR_app_isOrdered)
                     VALUES ('$id', 0, 1, '$appNotC', '$appNot1', '$appOrder', '$appName', '$isOrdered')";
        
        mysqli_query($conn,$appQuery);
    }  
}

if(isset($_POST['notifications'])){
    $notifications = $_POST['notifications'];

    foreach($notifications as $li){
        $notName = $li[0];
        $notNot1 = $li[1];
        $notNotC = $li[2];
        $notOrder = 0;

        $notQuery = "INSERT INTO APR_app (APR_app_sheetID, APR_app_isTemplate, APR_app_isApprover, APR_app_notifEnd, APR_app_notifBeginning, 
                                            APR_app_order, APR_app_name, APR_app_isOrdered)
                     VALUES ('$id', 0, 0, '$notNotC', '$notNot1', '$notOrder', '$notName', '$isOrdered')";
    
        mysqli_query($conn,$notQuery);
    }   
}

echo 1;

?>