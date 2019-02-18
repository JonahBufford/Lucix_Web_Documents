<?php

require_once 'connections.php';

if(isset($_POST['commentList'])){
    $detailArray = $_POST['commentList'];

    foreach($detailArray as $dataPiece){
        $ID = substr($dataPiece[0],1);
        $update = $dataPiece[1];

        $query = "UPDATE QCI
                  SET QCI_notes = '$update'
                  WHERE QCI_uid = '$ID'";

        odbc_exec($connENT,$query);
    }
    echo $ID;
}
else{
    echo 0;
}

?>