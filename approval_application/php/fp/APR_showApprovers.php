<?php

require_once 'connections.php';

$sort1list = "";
$sort3list = "";
$isOrdered = "0";

if(isset($_POST['id'])){
    $id = $_POST['id'];

    $listQuery = "SELECT *
                  FROM APR_app
                  WHERE APR_app_sheetID = '$id'";

    $listResult = mysqli_query($conn,$listQuery);

    while($i = mysqli_fetch_assoc($listResult)){
        $LIid = $i['APR_app_uid'];
        $name = $i['APR_app_name'];
        $not1 = $i['APR_app_notifBeginning'];
        $notC = $i['APR_app_notifEnd'];
        $order = $i['APR_app_order'];
        $isOrdered = $i['APR_app_isOrdered'];
        $isApp = $i['APR_app_isApprover'];

        $inID = "in" . $LIid;
        $not1id = "n1" . $LIid;
        $notCid = "nC" . $LIid;
        $orderid = "or" . $LIid;
        $counterid = "co" . $LIid;

        $nameArray = array();

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
                                <input value='$notC' type='hidden' id='$notCid'>
                                <input value='$order' type='hidden' id='$orderid'>
                                <input value='$counter' type='hidden' id='$counterid'>
                                $detailList
                             </li>";
        }
        
        else{
            $sort3list .= "<li class='ui-state-highlight' id='$LIid' name='sortable3'>$name
                                <input value='$name' type='hidden' id='$inID'>
                                <input value='$not1' type='hidden' id='$not1id'>
                                <input value='$notC' type='hidden' id='$notCid'>
                                <input value='$order' type='hidden' id='$orderid'>
                                <input value='$counter' type='hidden' id='$counterid'>
                                $detailList
                             </li>";
        }

    }
}

$output = "<table class='table1' id='approvalTable'>
                <tr>
                    <td>Approvals</td>
                    <td>Notify Only</td>
                </tr>
                <tr>
                    <td><ul id='sortable1' class='connectedSortable'>$sort1list</ul></td>
                    <td><ul id='sortable3' class='connectedSortable'>$sort3list</ul></td>
                </tr>
            </table>
            <table id='approvalData' class='table1'>
                <tr>
                    <td>Approval Data</td>
                </tr>
            </table>
            <input type='hidden' id='selectedLIid' value=''>
            <input value='$isOrdered' type='hidden' id='ordered'>
            ";

echo $output;

?>