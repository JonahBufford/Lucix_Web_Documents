<?php

require_once 'connections.php';

$user = $_POST['user'];
$temp = $_POST['temp'];
$name = $_POST['name'];
$notes = $_POST['notes'];
$isOrdered = $_POST['isOrdered'];

$date = date( 'Y-m-d');

if(isset($_POST['id'])){
    $isTemplate = $_POST['isTemplate'];
    $id = $_POST['id'];
    $query = "UPDATE APR_main
              SET APR_tempName = '$temp', APR_creator = '$user', APR_notes = '$notes', APR_name = '$name'
              WHERE APR_uid = '$id'";
    
    $result = mysqli_query($conn,$query);
}

else{
    $isTemplate = 0;
    $query = "INSERT INTO APR_main (APR_isTemplate,APR_tempName,APR_creator,APR_dateMade,APR_notes,APR_status,APR_name)
              VALUES ($isTemplate,'$temp','$user','$date','$notes','Pending','$name')";
    
    $result = mysqli_query($conn,$query);
    
    $getIdQ = "SELECT APR_uid
                FROM APR_main
                ORDER BY APR_uid DESC LIMIT 1";
    
    $getIdR = mysqli_query($conn,$getIdQ);
    
    $a = mysqli_fetch_assoc($getIdR);
    $id = $a['APR_uid'];
}

$deleteAssignedDetails = "DELETE FROM APR_details
                            WHERE APR_details_sheetId = '$id' AND APR_details_assignedToOther = 1";

mysqli_query($conn,$deleteAssignedDetails);



if(isset($_POST['detailArray'])){
    $detailArray = $_POST['detailArray'];
    if(is_array($detailArray)){
        foreach($detailArray as $dataPiece){
            $detail = $dataPiece[1];
            
            $query = "INSERT INTO APR_details (APR_details_isTemplate, APR_details_sheetID, APR_details_category)
                      VALUES ($isTemplate, '$id', '$detail')";
        
            mysqli_query($conn,$query);
        }

    }
}

if(isset($_POST['fullDetailArray'])){
    $detailArray = $_POST['fullDetailArray'];

    if(is_array($detailArray)){
        foreach($detailArray as $dataPiece){
            $detail = $dataPiece[1];
            $val = $dataPiece[2];
            
            $query = "INSERT INTO APR_details (APR_details_isTemplate, APR_details_sheetID, APR_details_category, APR_details_data)
                      VALUES ($isTemplate, '$id', '$detail', '$val')";
        
            mysqli_query($conn,$query);
        }

    }
}

if(isset($_POST['detailEditArray'])){
    $detailEditArray = $_POST['detailEditArray'];
    
    if(is_array($detailEditArray)){
        foreach($detailEditArray as $dataPiece){
            $fullId = $dataPiece[0];
            $editId = substr($fullId,6);
            $detail = $dataPiece[1];
            
            $query = "UPDATE APR_details
                      SET APR_details_category = '$detail'
                      WHERE APR_details_uid = '$editId'";
        
            mysqli_query($conn,$query);
        }

    }
}

if(isset($_POST['detailDeleteArray'])){
    $detailDeleteArray = $_POST['detailDeleteArray'];

    if(is_array($detailDeleteArray)){
        foreach($detailDeleteArray as $dataPiece){
            $fullId = $dataPiece[0];
            $editId = substr($fullId,6);
        
            $query = "DELETE FROM APR_details
                        WHERE APR_details_uid = '$editId'";
        
            mysqli_query($conn,$query);
        }

    }
}

if(isset($_POST['assignedDetailArray'])){
    $assignedDetailArray = $_POST['assignedDetailArray'];

    if(is_array($assignedDetailArray)){
        foreach($assignedDetailArray as $dataPiece){
            $detail = $dataPiece[1];
            $temp = $dataPiece[2];
            
            $query = "INSERT INTO APR_details (APR_details_isTemplate, APR_details_sheetID, APR_details_category, APR_details_assign, 
                                    APR_details_assignedToOther)
                      VALUES ($isTemplate, '$id', '$detail', '$temp', 1)";
        
            mysqli_query($conn,$query);
        }

    }
}

$deleteQuery = "DELETE FROM APR_app
                WHERE APR_app_sheetID = '$id'";

mysqli_query($conn,$deleteQuery);

if(isset($_POST['assignments'])){
    $assignments = $_POST['assignments'];

    if(is_array($assignments)){
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
    
            $appDate = $li[5];
            $appNotA = $li[6];
    
            $appQuery = "INSERT INTO APR_app (APR_app_sheetID, APR_app_isTemplate, APR_app_isApprover, APR_app_notifEnd, APR_app_notifBeginning, 
                                                APR_app_order, APR_app_name, APR_app_isOrdered, APR_app_dateSigned, APR_app_notifAvailable)
                         VALUES ('$id', $isTemplate, 1, '$appNotC', '$appNot1', '$appOrder', '$appName', '$isOrdered', '$appDate', '$appNotA')";
            
            mysqli_query($conn,$appQuery);
        }  

    }
    
}

if(isset($_POST['notifications'])){
    $notifications = $_POST['notifications'];
    if(is_array($notifications)){
        foreach($notifications as $li){
            $notName = $li[0];
            $notNot1 = $li[1];
            $notNotC = $li[2];
            $notOrder = 0;
            $notDate = $li[5];
    
            $notQuery = "INSERT INTO APR_app (APR_app_sheetID, APR_app_isTemplate, APR_app_isApprover, APR_app_notifEnd, APR_app_notifBeginning, 
                                                APR_app_order, APR_app_name, APR_app_isOrdered, APR_app_dateSigned)
                         VALUES ('$id', $isTemplate, 0, '$notNotC', '$notNot1', '$notOrder', '$notName', '$isOrdered', '$notDate')";
        
            mysqli_query($conn,$notQuery);
        }   

    }
}

echo $id;

?>