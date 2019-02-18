<?php

session_start ();

// import common files
require_once 'connections.php';

if (isset ( $_SESSION ['username'] )) {
    
    $userID = $_SESSION ['username'];
} else {
    $userID = "Unknown User";
}


if (isset ( $_POST ['baseNum'] )) {

    $nextItem = $_POST ['baseNum'];
    $itemType = $_POST ['thisType'];

    $sql = "INSERT INTO ITEM (ITM_ID, ITM_DASH, ITM_OWN, ITM_STA, ITM_TYPE)
    VALUES ($nextItem, 'XX', '$userID', 'Reserved', '$itemType')";
    
    $result = odbc_exec ( $connENT, $sql );

    echo "1";

} else {

    echo "Something went wrong. Post not set.";
}

?>