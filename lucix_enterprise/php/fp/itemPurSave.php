<?php

session_start ();

// import common files
require_once 'connections.php';

$rowID = "";
$piPM = "";
$pVen = "";
$pPN = "";
$pDoc = "";
$pExD = "";

if (isset ( $_POST ['rowID'] )) {

    $rowID = $_POST ['rowID'];
    $piPM = $_POST ['piPM'];
    $pVen = $_POST ['pVen'];
    $pPN = $_POST ['pPN'];
    $pDoc = $_POST ['pDoc'];
    $pExD = $_POST ['pExD'];

    $pOrM = trim($pOrM);

    $sql = "UPDATE ITEM
            SET ITM_PORM = '$piPM',
                ITM_PVEN = '$pVen',
                ITM_PPN = '$pPN',
                ITM_PDOC = '$pDoc',
                ITM_PEXD = '$pExD'
            WHERE ITM_uid = $rowID";

    $result = odbc_exec ( $connENT, $sql );

    if($result){
        echo "1";
    }

} else {

    echo "0";
}

?>