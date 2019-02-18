<?php

require_once 'connections.php';

if(isset($_POST['commentList'])){
    $detailArray = $_POST['commentList'];

    foreach($detailArray as $dataPiece){
        $tdraID = substr($dataPiece[0],2);
        $update = $dataPiece[1];
        if(substr($dataPiece[0],1,1) == 'c'){
            $col = 'TDRA_comments';
        }
        else if(substr($dataPiece[0],1,1) == 'r'){
            $col = 'TDRA_role';
        }
        else if(substr($dataPiece[0],1,1) == 'n'){
            $col = 'TDRA_assign';
        }

        $query = "UPDATE TDRA
                  SET $col = '$update'
                  WHERE TDRA_uid = '$tdraID'";

        mysqli_query($conn,$query);
    }
    echo 1;
}
else{
    echo 0;
}

?>