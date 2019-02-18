<?php

require_once 'connections.php';

$user = $_POST['user'];
$name = $_POST['temp'];
$notes = $_POST['notes'];
$isOrdered = $_POST['isOrdered'];
$assignments = $_POST['assignments'];
$notifications = $_POST['notifications'];

$date = date('Y-m-d');

if(isset($_POST['detailArray'])){
    $detailArray = $_POST['detailArray'];
    
    $query = "INSERT INTO APR_main (APR_isTemplate,APR_tempName,APR_creator,APR_dateMade,APR_notes,APR_status)
              VALUES (1,'$name','$user','$date','$notes','Template')";
    
    $result = mysqli_query($conn,$query);
    
    $getIdQ = "SELECT APR_uid
                FROM APR_main
                ORDER BY APR_uid DESC LIMIT 1";
    
    $getIdR = mysqli_query($conn,$getIdQ);
    
    $a = mysqli_fetch_assoc($getIdR);
    $id = $a['APR_uid'];
    
    foreach($detailArray as $dataPiece){
        $detail = $dataPiece[1];
    
        $query = "INSERT INTO APR_details (APR_details_isTemplate, APR_details_sheetID, APR_details_category)
                  VALUES (1, '$id', '$detail')";
    
        mysqli_query($conn,$query);
        echo $detail;
    }

    if(isset($_POST['assignedDetailArray'])){
        $assignedDetailArray = $_POST['assignedDetailArray'];
        foreach($assignedDetailArray as $dataPiece){
            $detail = $dataPiece[1];
            $name = $dataPiece[2];
            
            $query = "INSERT INTO APR_details (APR_details_isTemplate, APR_details_sheetID, APR_details_category, APR_details_assign, 
                                    APR_details_assignedToOther)
                      VALUES (1, '$id', '$detail', '$name', 1)";
        
            mysqli_query($conn,$query);
        }
    }

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

        $appNotA = $li[6];
        
        $appQuery = "INSERT INTO APR_app (APR_app_sheetID, APR_app_isTemplate, APR_app_isApprover, APR_app_notifBeginning, APR_app_notifEnd, 
                                APR_app_order, APR_app_name, APR_app_isOrdered, APR_app_notifAvailable)
                        VALUES ('$id', 1, 1, '$appNot1', '$appNotC', '$appOrder', '$appName', '$isOrdered', '$appNotA')";

        mysqli_query($conn,$appQuery);
    }  
    	
    foreach($notifications as $li){
        $notName = $li[0];
        $notNot1 = $li[1];
        $notNotC = $li[2];
        $notOrder = "";
        $notQuery = "INSERT INTO APR_app (APR_app_sheetID, APR_app_isTemplate, APR_app_isApprover, APR_app_notifBeginning, APR_app_notifEnd, 
                                APR_app_order, APR_app_name, APR_app_isOrdered)
                        VALUES ('$id', 1, 0, '$notNot1','$notNotC', '$notOrder', '$notName', '$isOrdered')";

        mysqli_query($conn,$notQuery);
    }
    echo $notQuery;
}
else{
    echo 1;
} 

?>