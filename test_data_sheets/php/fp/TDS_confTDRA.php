<?php

require_once 'connections.php';

$ID = $_POST['TDRMID'];

$query = "SELECT * FROM TDRA WHERE TDRA_sheetID = $ID";
$approvals = mysqli_query($conn, $query);
$table = "
        <button onclick='createNewApprovalSheetTDRA($ID)' class='mainBtn'>Add Reviewer</button>
        <table class='table2' id='table2'>
            <tr>
                <th>Role</th>
                <th>Reviewer Name</th>
                <th>Approval Status</th>
                <th>Signature</th>
                <th>Date Signed</th>
                <th>Comments</th>
            </tr> 
    ";
while($iterator = mysqli_fetch_assoc($approvals)){
    $role = $iterator['TDRA_role'];
    $assign = $iterator['TDRA_assign'];
    $disp = $iterator['TDRA_disp'];
    $sig = $iterator['TDRA_sig'];
    $date = $iterator['TDRA_date'];
    $comments = $iterator['TDRA_comments'];
    $table .= "
                    <tr id='nextItem'>
                        <td>$role</td>
                        <td>$assign</td>
                        <td>$disp</td>
                        <td>$sig</td>
                        <td>$date</td>
                        <td>$comments</td>
                    </tr>
             ";
}
$table .= "</table>";

echo $table;

?>