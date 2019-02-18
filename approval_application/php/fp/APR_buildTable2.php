<?php

require_once 'connections.php';

function createSelect($name,$val) {
    $conn = mysqli_connect("localhost","root","","approval_application");
    $select = "<select id='$name' onchange='buildTable2Filtered()'>
                    <option value=''>Select</option>";

    $apr = "APR_" . $name;

    $query = "SELECT DISTINCT $apr
              FROM APR_main
              WHERE APR_isTemplate = 0";

    $result = mysqli_query($conn,$query);

    while($i = mysqli_fetch_assoc($result)){
        $var = $i["$apr"];

        if($var == $val){
            $select .= "<option value='$var' selected>$var</option>";
        }

        else{
            $select .= "<option value='$var'>$var</option>";
        }
    }

    $select .= "</select>";

    return $select;
}

if(isset($_POST['template'])){
    $temp = $_POST['template'];
    $status = $_POST['status'];
    $creator = $_POST['creator'];

    $tempFilter = "";
    $statusFilter = "";
    $creatorFilter = "";

    if($temp != ""){
        $tempFilter = "AND APR_tempName = '$temp'";
    }

    if($status != ""){
        $statusFilter = "AND APR_status = '$status'";
    }

    if($creator != ""){
        $creatorFilter = "AND APR_creator = '$creator'";
    }

    $query = "SELECT *
                FROM APR_main
                WHERE APR_isTemplate = 0 $tempFilter $statusFilter $creatorFilter
                ORDER BY APR_uid DESC";
    
    $result = mysqli_query($conn,$query);
}

else{
    $temp = "";
    $status = "";
    $creator = "";

    $query = "SELECT *
                FROM APR_main
                WHERE APR_isTemplate = 0
                ORDER BY APR_uid DESC";
    
    $result = mysqli_query($conn,$query);
}

$tempSelect = createSelect("tempName", $temp);
$statusSelect = createSelect("status", $status);
$creatorSelect = createSelect("creator", $creator);

$table = "<table class='table2'>
            <tr>
                <th>ID</th>
                <th>Template</th>
                <th>Date Made</th>
                <th>Date Completed</th>
                <th>Status</th>
                <th>Created By</th>
            </tr>
            <tr>
                <td></td>
                <td>$tempSelect</td>
                <td></td>
                <td></td>
                <td>$statusSelect</td>
                <td>$creatorSelect</td>
            </tr>";

while($i = mysqli_fetch_assoc($result)){
    $temp = $i['APR_tempName'];
    $id = $i['APR_uid'];
    $date = $i['APR_dateMade'];
    $status = $i['APR_status'];
    $creator = $i['APR_creator'];

    if($i['APR_dateSigned'] == '0000-00-00'){
        $dateSigned = "";
    }
    else{
        $dateSigned = $i['APR_dateSigned'];
    }

    $table .= "<tr onclick='approvalPage(\"$id\")'>
                    <td>$id</td>
                    <td>$temp</td>
                    <td>$date</td>
                    <td>$dateSigned</td>
                    <td>$status</td>
                    <td>$creator</td>
                </tr>";
}

$table .= "</table>";

echo $table;

?>