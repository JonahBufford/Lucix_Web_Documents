<?php

session_start ();

// import common files
require_once 'connections.php';

$pDash = "";

if (isset ( $_POST ['rowID'] )) {

    $rowID = $_POST ['rowID'];

    $sql = "UPDATE ITEM
            SET ITM_STA = 'In ERP'
            WHERE ITM_uid = $rowID";

    $result = odbc_exec ( $connENT, $sql );

    if($result){
        echo "1";
    }

    //move folder
    $pBase =  $_POST["pBase"];
    $pDash =  $_POST["pDash"];

    if ($pDash == ""){
        $folder = $pBase . "/";
    } else {
        $folder = $pBase . "-" . $pDash . "/";
    }
    
    $orig_dir = "//CMDATASTORAGE/Workpad/Lucix_Applications/ItemID_Files/" . $folder;

    $new_dir = "//CMDATASTORAGE/Workpad/Lucix_Applications/ItemID_Files/_Completed/" . $folder;

    if (file_exists ( $orig_dir )){
        rename($orig_dir, $new_dir);
    }


} else {

    echo "0";
}

?>