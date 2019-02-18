<?php

require_once 'connections.php';

$name = $_POST['empName'];
$id = $_POST['id'];
$user = $_POST['user'];

$query = "SELECT *
          FROM APR_app
          WHERE APR_app_uid = '$id'";

$result = mysqli_query($conn,$query);

$app = mysqli_fetch_assoc($result);

$notes = $app['APR_app_notes'];
$order = $app['APR_app_order'];
$sheetId = $app['APR_app_sheetID'];
$date = $app['APR_app_dateSigned'];

if($date != '0000-00-00'){ 
    $approved = "Approved $date";
}
else{
    $approved = "Not Approved";
}

$detailQuery = "SELECT *
                FROM APR_details
                WHERE APR_details_assign = '$name' AND APR_details_sheetID = '$sheetId'";

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

if($user == $name){
    $notesId =  "notes" . $id;
    $noteSpace = "<textarea onchange='updateNotes(\"$id\", \"$notesId\")' id='$notesId'>$notes</textarea>";
    $approveButton = "<button onclick='approve($id)'>Approve</button>";
}

else{
    $noteSpace = $notes;
    $approveButton = "";
}

$output = "<tr>
                <th>Approval Data</th>
            </tr>
            <tr>
                <td>$name</td>
            </tr>
            <tr>
                <td>$noteSpace</td>
            </tr>
            <tr>
                <td>Details assigned to reviewer: $detailList</td>
            </tr>
            <tr class='orderInfo'>
                <td>Order Number: $order</td>
            </tr>
            <tr>
                <td>$approved</td>
            </tr>
            <tr>
                <td>$approveButton</td>
            </tr>";

echo $output;

?>