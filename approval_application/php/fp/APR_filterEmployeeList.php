<?php

require_once 'connections.php';

$dept = $_POST['dept'];
$empCounter = $_POST['counter'];

if($dept == 'all'){
    $and = "";
}
else{
    $and = "AND emp_dept = '$dept'";
}

$query = "SELECT emp_name
    FROM tbl_EMP
    WHERE emp_status ='1' AND emp_locX IS NOT NULL $and
    ORDER BY emp_name";

$result = odbc_exec ( $connENT, $query );

$employeeList = "<ul id='sortable2' class='connectedSortable'>";

if (odbc_num_rows ( $result ) > 0) {
    while(odbc_fetch_row($result)){
        $name = odbc_result($result, "emp_name");
        $LIid = "li" . $empCounter;
        $inID = "in" . $LIid;
        $not1id = "n1" . $LIid;
        $notAid = "nA" . $LIid;
        $notCid = "nC" . $LIid;
        $orderid = "or" . $LIid;
        $counterid = "co" . $LIid;
        $dateId = "da" . $LIid;

        $employeeList .= "<li class='ui-state-highlight' id='$LIid' name='sortable2'>$name
                            <input value='$name' type='hidden' id='$inID'>
                            <input value='0' type='hidden' id='$not1id'>
                            <input value='0' type='hidden' id='$notAid'>
                            <input value='0' type='hidden' id='$notCid'>
                            <input value='0' type='hidden' id='$orderid'>
                            <input value='1' type='hidden' id='$counterid'>
                            <input value='' type='hidden' id='$dateId'>
                        </li>";
        $empCounter++;
    }
}

$employeeList .= "</ul><input type='hidden' id='empCounter' value='$empCounter'>";

echo $employeeList;

?>