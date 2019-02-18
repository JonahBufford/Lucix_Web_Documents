<?php

session_start ();

require_once 'connections.php';

$user = '';
if(isset($_SESSION['username'])){
    $user = $_SESSION['username'];
}

$ID = $_POST['id'];

$admin = $_SESSION['admin'];

$tdrmQ = "SELECT *
          FROM TDRM
          WHERE TDRM_uid = '$ID'";

$tdrm = mysqli_query($conn,$tdrmQ);
$iterator = mysqli_fetch_assoc($tdrm);
$vend = $iterator['TDRM_Vendor'];    
$po = $iterator['TDRM_PO_Number'];
$dateCode = $iterator['TDRM_Date_Code'];
$program = $iterator['TDRM_Program'];
$itemID = $iterator['TDRM_Item_ID'];
$scdNo = $iterator['TDRM_SCD_No'];
$tdrmStatus = $iterator['TDRM_status'];
$mir = $iterator['TDRM_mir'];
$create = $iterator['TDRM_created_by'];
$file = $iterator['TDRM_file'];
$dateCreated = $iterator['TDRM_date'];
$dateSigned = $iterator['TDRM_dateSigned']; 
$desc = $iterator['TDRM_desc'];

$tdrmRow = "
                <td>$itemID</td>
                <td>$desc</td>
                <td>$vend</td>
                <td>$po</td>
                <td>$dateCode</td>
                <td>$program</td>
                <td>$scdNo</td>
                <td>$tdrmStatus</td>
                <td>$file</td>
                <td>$mir</td>
                <td>$create</td>
                <td>$dateCreated</td>
                <td>$dateSigned</td>
            ";

$dwgList = "<table class='table2' id='drawings'>
                <tr>
                    <th>Matching Drawings Found</th>
                </tr>";
                
$file1 = "//CMDATASTORAGE/Workpad/Jonah B/ItemID Files/" . $ID . "/*.pdf";
                
foreach ( glob ( "$file1" ) as $filename ) {
    $dwgList .= "<tr>
                    <td><a style='color:blue' href='fp/TDS_openDwg.php?dwg=" . $filename . "' target='_blank'>Open</a></td>
                </tr>";
}

$dwgList .= "</table>";

$query = "SELECT *
          FROM TDRA
          WHERE TDRA_sheetID = '$ID'";      

$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) == 0 ){
    echo '';
}

else{
    $adminButtons = '';
    $isAdmin = FALSE;
    $approved = TRUE;
    if(in_array($user,$admin)){
        $isAdmin = TRUE;
    }
    if($tdrmStatus == 'Active' || $tdrmStatus == 'Pending'){
        $addReviewerButton = "<button onclick='addReviewer($ID)' class='bigBtn'>Add Reviewer</button>";

        if($isAdmin){
            $progQuery = "SELECT Program
                        FROM tblCustomers
                        WHERE Available = 'True'";

            $progResult = odbc_exec ( $connOLTP, $progQuery );

            // check to see if there are any results

            $progList = "";

            if (odbc_num_rows ( $progResult) > 0) { // start if there is a result
                                     
                // open the pending step(s)
                                                 
                // check to see what type of step is about the be opened
                
                $progList = "<select id='program' class='mainSelect' onchange='revealTDRM()'>
                                <option value='' selected>Select</option>";
            
                $progArray = array();
                $counter = 0;
                while ( odbc_fetch_row ( $progResult ) ) {
                    if(odbc_result( $progResult, 1 ) == $program){
                        $progArray[$counter] = "<option value='" . odbc_result( $progResult, 1 ) . "' selected>" . odbc_result ( $progResult, 1 ) . "</option>";
                        $counter++;
                    }
                    else{
                        $progArray[$counter] = "<option value='" . odbc_result( $progResult, 1 ) . "'>" . odbc_result ( $progResult, 1 ) . "</option>";
                        $counter++;
                    }
                }
                
                sort($progArray);
            
                $iterator = 0;
                while($iterator < $counter){
                    $progList .= $progArray[$iterator];
                    $iterator++;
                }
                
                $progList .= "</select>";
            }
            $tdrmRow = "
                            <td><textarea name='tdrmInput' oninput='revealTDRM()' id='itemID'>$itemID</textarea></td>
                            <td>$desc</td>
                            <td><textarea name='tdrmInput' oninput='revealTDRM()' id='vend'>$vend</textarea></td>
                            <td><textarea name='tdrmInput' oninput='revealTDRM()' id='po'>$po</textarea></td>
                            <td><textarea name='tdrmInput' oninput='revealTDRM()' id='dateCode'>$dateCode</textarea></td>
                            <td>$progList</td>
                            <td><textarea name='tdrmInput' oninput='revealTDRM()' id='scdNo'>$scdNo</textarea></td>
                            <td>$tdrmStatus</td>
                            <td>$file<input type='button' class='pBtn' value='add file' onclick='uploadDoc()' id='addFile'></td>
                            <td><textarea name='tdrmInput' oninput='revealTDRM()' id='mir'>$mir</textarea></td>
                            <td>$create</td>
                            <td>$dateCreated</td>
                            <td>$dateSigned</td>
                        ";
        }
        $table = "
                  <table class='table2' id='tdraTable'>
                    <tr>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Comments</th>
                        <th class='buttons'>Buttons</th>
                    <tr>
                  ";
        
        while($i = mysqli_fetch_assoc($result)){
            $role = $i['TDRA_role'];
            $name = $i['TDRA_assign'];
            $tdraStatus = $i['TDRA_disp'];
            $date = $i['TDRA_date'];
            $tdraID = $i['TDRA_uid'];
            $comments = $i['TDRA_comments'];

            if($tdraStatus != 'Approved'){
                $approved = FALSE;
            }

            $buttons = "";
            $rolename = "
                            <td>$role</td>
                            <td>$name</td>
                        ";
            $comSpace = "$comments";

            if($tdraStatus == "Pending" && ($user == $name || $isAdmin)){
                $comSpace = "<textarea id='uc$tdraID' name='tdraInput' oninput='reveal($tdraID,$isAdmin)'>$comments</textarea>";
                if($isAdmin){
                    $empquery = "SELECT strEmpName
                                FROM tblEmployees
                                WHERE strAccess <> 'INACTIVE'
                                ORDER BY strEmpName";

                    // store the results in an array
                    $empresult = odbc_exec ( $connOLTP, $empquery );

                    // check to see if there are any results

                    $employees = "";

                    if (odbc_num_rows ( $empresult ) > 0) { // start if there is a result
                                                        
                        // open the pending step(s)
                                                        
                        // check to see what type of step is about the be opened
                        
                        $employees = "<select id='un$tdraID' name='tdraInput' class='norm' class='mainSelect' oninput='reveal($tdraID,$isAdmin)'>
                                        <option value='select'>Select</option>";
                        
                        while ( odbc_fetch_row ( $empresult ) ) {
                            if(odbc_result ( $empresult, 1 ) == $name){
                                $employees .= "<option value='" . odbc_result ( $empresult, 1 ) . "' selected>" . odbc_result ( $empresult, 1 ) . "</option>";
                            }
                            else{
                                $employees .= "<option value='" . odbc_result ( $empresult, 1 ) . "'>" . odbc_result ( $empresult, 1 ) . "</option>";
                            }
                        }
                        
                        $employees .= "</select>";
                    }
                    $rolename = "
                                    <td><textarea id='ur$tdraID' name='tdraInput' oninput='reveal($tdraID,$isAdmin)'>$role</textarea></td>
                                    <td>$employees</td>
                                ";
                    $buttons = "
                                    <button onclick='approveTDRA($tdraID)'>Approve</button>
                                    <button onclick='rejectTDRA($tdraID)'>Reject</button>
                                    <button onclick='deleteTDRA($tdraID)'>Delete</button>
                                    <button onclick='saveCommentsAdmin($tdraID)' style='display:none' id='sc$tdraID'>Save Comments</button>
                                ";
                }
                else{
                    $buttons = "
                                    <button onclick='approveTDRA($tdraID)'>Approve</button>
                                    <button onclick='rejectTDRA($tdraID)'>Reject</button>
                                    <button onclick='deleteTDRA($tdraID)'>Delete</button>
                                    <button onclick='saveComments($tdraID)' style='display:none' id='sc$tdraID'>Save Comments</button>
                                ";
                }
            }
        
            else if($isAdmin){
                $buttons = "
                            <button onclick='resetTDRA($tdraID)'>Reset</button>
                            ";
            }

            else if($tdraStatus == "Rejected"){
                $buttons = "
                                    <button onclick='approveTDRA($tdraID)'>Approve</button>
                            ";
            }
            $table .= "
                       <tr>
                            $rolename
                            <td>$tdraStatus</td>
                            <td>$date</td>
                            <td>$comSpace</td>
                            <td class='buttons'>
                                $buttons
                                <button onclick='sendEmail(\"$ID\",\"$name\")'>Notify</button>
                            </td>
                        </tr>
                      ";
        }
        
        $table .="</table>";
    }
    
    else{
        $addReviewerButton = "";
        $table = "
                  <table class='table2' id='tdraTable'>
                    <tr>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Comments</th>
                    <tr>
                  ";
        
        while($i = mysqli_fetch_assoc($result)){
            $role = $i['TDRA_role'];
            $name = $i['TDRA_assign'];
            $tdraStatus = $i['TDRA_disp'];
            $date = $i['TDRA_date'];
            $tdraID = $i['TDRA_uid'];
            $comments = $i['TDRA_comments'];
            
            $table .= "
                       <tr>
                            <td>$role</td>
                            <td>$name</td>
                            <td>$tdraStatus</td>
                            <td>$date</td>
                            <td>$comments</td>
                        </tr>
                      ";
        }
        
        $table .="</table>";
        
    }

    if($isAdmin){
        if($tdrmStatus == 'Active'){
            if($approved){
                $adminButtons = "<button onclick='completeTDRM($ID)' class='bigBtn'>Complete TDRS</button>";
            }
        }

        else{
            $adminButtons = "<button onclick='resetTDRM($ID)' class='bigBtn'>Reset TDRS</button>";
        }

        $adminButtons .= "<button onclick='saveTDRM($ID)' class='bigBtn' style='display:none' id='saveTDRM'>Save TDRM Changes</button>";
    }

    if($user == ""){
        $addReviewerButton = "";
    }

    echo "<div class='titleRow'>
            Open TDR <span class='printOnly'> $ID</span>
            <input id='enterID' class='item' class='baseBox' value=$ID>
            <button onclick='editPrev()' class='mainBtn'>Go</button>
            <button onclick='back()' class='mainBtn'>Back</button>
            <button onclick='window.print()' class='mainBtn'>Print</button>
            $adminButtons
          </div>
          <table class='table2'>
            <tr>
                <th>Item ID</th>
                <th>Description</th>
                <th>Vendor</th>
                <th>PO Number</th>
                <th>Date Code</th>
                <th>Program</th>
                <th>SCD Number</th>
                <th>Status</th>
                <th>File</th>
                <th>MIR</th>
                <th>Created By</th>
                <th>Created On</th>
                <th>Signed Off</th>
            </tr>
            <tr>
                $tdrmRow
            </tr>
          </table>
          <div id='fileUpload' hidden>
            <form action='fp/TDS_itemFileUpload.php' method='post' enctype='multipart/form-data' target='_self'>
                Select file to upload:
                <br>
                <input type='file' name='fileToUpload' id='fileToUpload' class='choseBtn' onclick='showUpBtn()'>
                <br>
                <input type='submit' value='Upload File' name='submit' class='pBtn' onclick='buildProcessingForm($ID)' id='upBtn' hidden>
                <input type='hidden' value='$ID' name='uid'>
            </form>
          </div>
          $dwgList
          <div class='titleRow'>
            Approval List
            $addReviewerButton
            <button onclick='saveAll($ID)' style='display:none' class='bigBtn' id='saveAll'>Save Details</button>
          </div>
          $table";
}

?>