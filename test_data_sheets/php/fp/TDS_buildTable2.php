<?php

session_start ();

require_once 'connections.php';

error_reporting(0);

$user = "";

$name = $_POST['name'];
$status = $_POST['filter'];
$query = "SELECT *
          FROM TDRM JOIN TDRA
          ON TDRM_uid = TDRA_sheetID
          WHERE TDRM_status $status AND TDRA_assign $name
          ORDER BY TDRM_uid DESC";
          
$result = mysqli_query($conn, $query);

$statusText = $_POST['statusText'];
$nameText = $_POST['nameText'];

$table = "<div class='titleRow'>$statusText test data review sheets";

if($nameText != "All Employees"){
    $table .= " with $nameText as an assigned reviewer </div>
              ";
}
else{    
    $table .= " </div>
                ";
}

$table .= "<table class='table2'>
            <tr>
                <th>TDRS Number</th>
                <th>Item ID</th>
                <th>Vendor</th>
                <th>PO Number</th>
                <th>Program</th>
                <th>SCD Number</th>
                <th>Status</th>
                <th>File</th>
                <th>MIR</th>
                <th>Open Approvals</th>
                <th>Completed Approvals</th>
                <th>Created Date</th>
                <th>Date Signed</th>
            </tr>
            ";


$array = array();
$maxId = 0;
while($iterator = mysqli_fetch_assoc($result)){
    $uid = $iterator['TDRM_uid'];
    $vend = $iterator['TDRM_Vendor'];    
    $po = $iterator['TDRM_PO_Number'];
    $dateCode = $iterator['TDRM_Date_Code'];
    $program = $iterator['TDRM_Program'];
    $itemID = $iterator['TDRM_Item_ID'];
    $scdNo = $iterator['TDRM_SCD_No'];
    $status = $iterator['TDRM_status'];
    $file = $iterator['TDRM_file'];
    $mir = $iterator['TDRM_mir'];
    $create = $iterator['TDRM_created_by'];
    $date = $iterator['TDRM_date'];
    $dateSigned = $iterator['TDRM_dateSigned'];

    if(!in_array($uid,$array)){
        $listQ = "SELECT *
                  FROM TDRA
                  WHERE TDRA_sheetID = '$uid'";
    
        $listResult = mysqli_query($conn,$listQ);
    
        $open = "";
        $closed = "";
        $openAdd = FALSE;
        $closedAdd = FALSE;
    
        
    
        $listDone = false;
    
        while($i = mysqli_fetch_assoc($listResult)){
            $disp = $i['TDRA_disp'];
            if($disp == 'Pending'){
                if($openAdd){
                    $open .= ", ";
                }
                $open .= $i['TDRA_assign'];
                $openAdd = TRUE;
            }
            else{
                if($closedAdd){
                    $closed .= ", ";
                }
                $closed .= $i['TDRA_assign'];
                $closedAdd = TRUE;
            }
        }
        $table .= "
                                <tr onclick='buildProcessingForm($uid)' class='click'>
                                    <td>$uid</td>
                                    <td>$itemID</td>
                                    <td>$vend</td>
                                    <td>$po</td>
                                    <td>$program</td>
                                    <td>$scdNo</td>
                                    <td>$status</td>
                                    <td>$file</td>
                                    <td>$mir</td>
                                    <td>$open</td>
                                    <td>$closed</td>
                                    <td>$date</td>
                                    <td>$dateSigned</td>
                                </tr>
                                ";
        $array[] = $uid;
    }

}
$table .= "</table>";

echo $table;

?>