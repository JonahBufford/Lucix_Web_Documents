<?php

require_once 'connections.php';

$query = "SELECT emp_id, emp_name, emp_email, emp_dept, emp_locX, emp_locY, emp_phone
    FROM tbl_EMP
    WHERE emp_status ='1' AND emp_locX IS NOT NULL
    ORDER BY emp_name";

$result = odbc_exec ( $connENT, $query );

// check to see if there are any results

$empCounter = 1;

if (odbc_num_rows ( $result ) > 0) {

    $employeeList = "<ul id='sortable2' class='connectedSortable'>";

    while(odbc_fetch_row($result)){
        $LIid = "li" . $empCounter;
        $name = odbc_result($result, "emp_name");
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

    $employeeList .= "</ul>";

} else {
        $employeeList = "No Records found";
}

$deptQuery = "SELECT DISTINCT emp_dept
                FROM tbl_EMP
                ORDER BY emp_dept";

$deptResult = odbc_exec ($connENT,$deptQuery);

if(odbc_num_rows($deptResult)){
    $deptList = "<select onchange='deptSelected()' id='deptSelect'>
                    <option value='all'>All Departments</option>";

    while(odbc_fetch_row($deptResult)){
        $department = odbc_result($deptResult, "emp_dept");
        
        if($department != ""){
            $deptList .= "<option value='$department'>$department</option>";
        }
    }

    $deptList .= "</select>";
}

else{
    $deptList = "";
}

$sort1list = "";
$sort3list = "";
$isOrdered = "0";

if(isset($_POST['id'])){
    $id = $_POST['id'];

    $listQuery = "SELECT *
                  FROM APR_app
                  WHERE APR_app_sheetID = '$id'";

    $listResult = mysqli_query($conn,$listQuery);
    $nameArray = array();

    while($i = mysqli_fetch_assoc($listResult)){
        $LIid = $i['APR_app_uid'];
        $name = $i['APR_app_name'];
        $not1 = $i['APR_app_notifBeginning'];
        $notA = $i['APR_app_notifAvailable'];
        $notC = $i['APR_app_notifEnd'];
        $order = $i['APR_app_order'];
        $isOrdered = $i['APR_app_isOrdered'];
        $isApp = $i['APR_app_isApprover'];
        $date = $i['APR_app_dateSigned'];

        $inID = "in" . $LIid;
        $not1id = "n1" . $LIid;
        $notAid = "nA" . $LIid;
        $notCid = "nC" . $LIid;
        $orderid = "or" . $LIid;
        $counterid = "co" . $LIid;
        $dateId = "da" . $LIid;


        $detailList = "";
        $counter = 1;

        if(!in_array($name, $nameArray)){

            $detailQuery = "SELECT *
                            FROM APR_details
                            WHERE APR_details_assign = '$name' AND APR_details_sheetID = '$id'";
            
            $detailResult = mysqli_query($conn,$detailQuery);

            while($i = mysqli_fetch_assoc($detailResult)){
                $detailList .= "<input value ='"  . $i['APR_details_category'] . "' type='hidden' id='" . $counter . "aD" . $LIid ."'>";
                $counter++;
            }

            $nameArray[] = $name;
        }

        if($isApp == 1){

            $sort1list .= "<li class='ui-state-highlight' id='$LIid' name='sortable1'>$name
                                <input value='$name' type='hidden' id='$inID'>
                                <input value='$not1' type='hidden' id='$not1id'>
                                <input value='$notA' type='hidden' id='$notAid'>
                                <input value='$notC' type='hidden' id='$notCid'>
                                <input value='$order' type='hidden' id='$orderid'>
                                <input value='$counter' type='hidden' id='$counterid'>
                                <input value='$date' type='hidden' id='$dateId'>
                                $detailList
                             </li>";
        }
        
        else{
            $sort3list .= "<li class='ui-state-highlight' id='$LIid' name='sortable3'>$name
                                <input value='$name' type='hidden' id='$inID'>
                                <input value='$not1' type='hidden' id='$not1id'>
                                <input value='$notA' type='hidden' id='$notAid'>
                                <input value='$notC' type='hidden' id='$notCid'>
                                <input value='$order' type='hidden' id='$orderid'>
                                <input value='$counter' type='hidden' id='$counterid'>
                                <input value='$date' type='hidden' id='$dateId'>
                                $detailList
                             </li>";
        }

    }
}

$output = "<div id='scroll'>
                <table class='table1' id='approvalTable'>
                    <tr>
                        <td>Approvals</td>
                        <td>Source List</td>
                        <td>Notify Only</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>$deptList</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><ul id='sortable1' class='connectedSortable'>$sort1list</ul></td>
                        <td id='empListCell'>$employeeList<input type='hidden' id='empCounter' value='$empCounter'></td>
                        <td><ul id='sortable3' class='connectedSortable'>$sort3list</ul></td>
                    </tr>
                </table>
            </div>
            <table id='approvalData' class='table1'>
                <tr>
                    <td colspan='3'>Approval Data</td>
                </tr>
            </table>
            <input type='hidden' id='selectedLIid' value=''>
            <input value='$isOrdered' type='hidden' id='ordered'>";

echo $output;

?>