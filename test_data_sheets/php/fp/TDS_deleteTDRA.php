<?php

session_start ();

require_once 'connections.php';

$ID = $_POST['id'];

$query = "SELECT *
          FROM TDRA
          WHERE TDRA_uid = '$ID'";

$selected = mysqli_query($conn,$query);

if(mysqli_num_rows($selected) > 0){
    $tdrm = mysqli_fetch_assoc($selected);
    $tdrmid = $tdrm['TDRA_sheetID'];

    $allQuery = "SELECT *
                 FROM TDRA
                 WHERE TDRA_sheetID = '$tdrmid'";

    $all = mysqli_query($conn,$allQuery);

    if(mysqli_num_rows($all) > 1){
        $delete = "DELETE
                  FROM TDRA
                  WHERE TDRA_uid = '$ID'";
        
        mysqli_query($conn,$delete);
        
        $findAll = "SELECT *
                    FROM TDRA
                    WHERE TDRA_sheetID = '$tdrmid'";
        $result = mysqli_query($conn,$findAll); 
        
        echo $tdrmid;
    }

    else{
        echo 'x';
    }
    
}
else{
    echo 'x';
}

?>