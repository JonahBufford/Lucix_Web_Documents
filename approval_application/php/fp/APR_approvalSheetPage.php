<?php

require_once 'connections.php';

$id = $_POST['id'];
$user = $_POST['user'];

$query = "SELECT *
          FROM APR_main
          WHERE APR_uid = '$id'";

$result = mysqli_query($conn,$query);

$main = mysqli_fetch_assoc($result);

$tempName = $main['APR_tempName'];
$notes = $main['APR_notes'];
$creator = $main['APR_creator'];
$mainStatus = $main['APR_status'];
$appName = $main['APR_name'];
$adminButtons = "";
$mainnotes = $notes;

if($user == $creator){
    if($mainStatus == "Pending"){
        $approvalQ = "SELECT APR_app_dateSigned
                      FROM APR_app
                      WHERE APR_app_sheetID = '$id' AND APR_app_dateSigned = '0000-00-00' AND APR_app_isApprover = 1";
    
        $approvalR = mysqli_query($conn,$approvalQ);
    
        $adminButtons = "<button onclick='editApprovalSheet(\"$id\",\"$tempName\")'>Edit Approval</button>
                         <button onclick='cancel(\"$id\")'>Cancel Approval</button>";
    
        if(mysqli_num_rows($approvalR) > 0){
        }
    
        else{
            $adminButtons .= " <button onclick='approveSheet(\"$id\")'>Approve</button>";
        }
    
        $mainnotes = "<textarea id='mainNotes' onchange='saveMainNotes($id)'>$notes</textarea>";
    }

    else{
        $adminButtons = "<button onclick='resetApprovalSheet($id)'>Reset</button>";
    }
}

$detailQuery = "SELECT *
                FROM APR_details
                WHERE APR_details_sheetID = '$id'";

$detailResult = mysqli_query($conn,$detailQuery);

$detailTable = "<table class='table2'>
                    <tr>
                        <th>Name</th>
                        <th>Assigned Approver</th>
                        <th>Data</th>
                    </tr>";

while($i = mysqli_fetch_assoc($detailResult)){
    $category = $i['APR_details_category'];
    $assignedToOther = $i['APR_details_assignedToOther'];
    $value = $i['APR_details_data'];
    $detailId = $i['APR_details_uid'];

    if($assignedToOther){
        $assigned = $i['APR_details_assign'];
    }

    else{
        $assigned = $creator;
    }

    if($user == $assigned && $mainStatus == "Pending"){
        $textAreaId = "data" . $detailId;
        $dataField = "<textarea onchange='saveDetailData(\"$detailId\", \"$textAreaId\")' id='$textAreaId'>$value</textarea>";
    }

    else{
        $dataField = "$value";
    }

    $detailTable .= "<tr>
                        <td>$category</td>
                        <td>$assigned</td>
                        <td>$dataField</td>
                    </tr>";
}

$detailTable .= "</table>";

$appTable = "<table class='table2'>
                <tr>
                    <th>Approver Name</th>
                    <th>Details assigned to reviewer</th>
                    <th>Approvals to be completed before this</th>
                    <th>Date Approved</th>
                    <th>Buttons</th>
                </tr>";

$listQuery = "SELECT *
                FROM APR_app
                WHERE APR_app_sheetID = '$id' AND APR_app_isApprover
                ORDER BY APR_app_order ASC";

$listResult = mysqli_query($conn,$listQuery);

while($i = mysqli_fetch_assoc($listResult)){
    $LIid = $i['APR_app_uid'];
    $name = $i['APR_app_name'];
    $order = $i['APR_app_order'];
    $isOrdered = $i['APR_app_isOrdered'];
    $dateSigned = $i['APR_app_dateSigned'];

    $detailQuery = "SELECT *
                    FROM APR_details
                    WHERE APR_details_assign = '$name' AND APR_details_sheetID = '$id'";

    $detailResult = mysqli_query($conn,$detailQuery);

    $first = TRUE;
    $detailList = "";

    while($i = mysqli_fetch_assoc($detailResult)){
        $detailCategory = $i['APR_details_category'];
        if(!$first){
            $detailList .= ", ";
        }

        $first = FALSE;
        $detailList .= $detailCategory;
    }

    $appsReady = TRUE;

    $appList = "";

    if($isOrdered == 1){
        $orderQuery = "SELECT *
                        FROM APR_app
                        WHERE APR_app_order < '$order' AND APR_app_order != 0 AND APR_app_sheetID = '$id' 
                        AND APR_app_dateSigned = '0000-00-00'";

        $orderResult = mysqli_query($conn,$orderQuery);

        $firstApproval = TRUE;

        if(mysqli_num_rows($orderResult) > 0){
            $appsReady = FALSE;

            while($i = mysqli_fetch_assoc($orderResult)){
                $appName = $i['APR_app_name'];
                if(!$firstApproval){
                    $appList .= ", ";
                }

                $firstApproval = FALSE;

                $appList .= $appName;
            }
        }
    }

    $appButtons = "";

    if($dateSigned == '0000-00-00'){
        if($appsReady && $name == $user && $mainStatus == "Pending"){
            $appButtons = "<button onclick = 'approve($LIid, $id)'>Approve</button>";
        }
        $dateSigned = "";
    }

    else{
        if(($name == $user || $name = $creator) && $mainStatus == "Pending"){
            $appButtons = "<button onclick='undoApprove($LIid, $id)'>Undo Approval</button>";
        }
    }


    $appTable .= "<tr>
                      <td>$name</td>
                      <td>$detailList</td>
                      <td>$appList</td>
                      <td>$dateSigned</td>
                      <td>$appButtons</td>
                  </tr>";

}

$appTable .= "</table>";

$dwgList = "<table class='table2' id='files'>
                <tr>
                    <th>Title</th>
                    <th>Link</th>
                    <th>Added By</th>
                </tr>";
                
$file1 = "//CMDATASTORAGE/Workpad/Jonah B/APR_Files/" . $id . "/*.pdf";
                
foreach ( glob ( "$file1" ) as $filename ) {

    $fileQuery = "SELECT *
                  FROM APR_files
                  WHERE APR_files_path = '$filename'";

    $fileResult = mysqli_query($conn,$fileQuery);

    $fileDetails = mysqli_fetch_assoc($fileResult);

    $title = $fileDetails['APR_files_title'];
    $addedBy = $fileDetails['APR_files_addedBy'];
    $nameOfFile = $fileDetails['APR_files_name'];

    $dwgList .= "<tr>
                    <td>$title</td>
                    <td><a style='color:blue' href='fp/APR_openDwg.php?file=" . $filename . "' target='_blank'>$nameOfFile</a></td>
                    <td>$addedBy</td>
                </tr>";
}

$dwgList .= "</table>";

$output = "<div class='titleRow'>Approval $id</div>
            <div class='titleRow'>Template: $tempName</div>
            <div class='titleRow'>Name: $appName</div>
            <div>$adminButtons <button onclick='back()'>Back</button></div>
            <div>$mainnotes</div>
            $detailTable
            $appTable
            $dwgList";

echo $output;

?>