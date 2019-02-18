<?php

require_once 'connections.php';

if(isset($_POST['type']) && isset($_POST['creator'])){

    $type = $_POST['type'];
    $creator = $_POST['creator'];
    
    $date = date('Y-m-d');
    
    $query = "INSERT INTO APR_main (APR_tempName, APR_creator, APR_dateMade)
              VALUES ('$type', '$creator', '$date')";
    
    mysqli_query($conn,$query);
    
    $sql = "SELECT APR_uid FROM APR_main ORDER BY APR_uid DESC LIMIT 1";
    $ID = mysqli_query( $conn, $sql );
    
    if($ID){
        if (mysqli_num_rows ( $ID ) > 0) {
    
            $row = mysqli_fetch_assoc($ID);
            $id = $row['APR_uid'];
    
        }
    }
    
    if(isset($_POST['details'])){
        $details = $_POST['details'];
    
        foreach($details as $dataPiece){
            $category = $dataPiece[0];
            $data = $dataPiece[1];
    
            $insertDetail = "INSERT INTO APR_details (APR_details_sheetID, APR_details_category, APR_details_data)
                            VALUES ('$id','$category','$data')";
    
            mysqli_query($conn,$insertDetail);
        }
        echo $id;
    }
    else{
        echo 11;
    }
}
else{
    echo 0;
}

?>