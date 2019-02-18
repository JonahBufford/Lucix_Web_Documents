<?php

session_start ();

// import common files
require_once 'connections.php';

if (isset ( $_SESSION ['username'] )) {
    
    $userID = $_SESSION ['username'];

} else {
    $userID = "Unknown User";
}


if (isset ( $_POST ['itemQty'] )) {

    $iterations = $_POST ['itemQty'];

    //get starting Item ID
    $query = "SELECT TOP 1 ITM_ID
                FROM ITEM
                ORDER BY ITM_ID Desc";

    $result = odbc_exec ( $connENT, $query );

    if (odbc_num_rows ( $result ) > 0) {
        
        while(odbc_fetch_row($result)){

            $nextItem = odbc_result($result,"ITM_ID");
        }
    }

    $nextItem++;

    $firstItem = $nextItem;

    for ($x = 0; $x < $iterations; $x++) {

        $sql = "INSERT INTO ITEM (ITM_ID, ITM_OWN, ITM_STA, ITM_TYPE)
                VALUES ($nextItem, '$userID', 'Reserved', '0')";
                
                $result = odbc_exec ( $connENT, $sql );

        $nextItem++;
    } 

    echo $firstItem;

} else {

    echo "0";
}

?>