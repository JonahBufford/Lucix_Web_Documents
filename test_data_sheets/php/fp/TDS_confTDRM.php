<?php

require_once 'connections.php';

$ID = $_POST['TDRMID'];

$result = mysqli_query($conn, "SELECT * FROM TDRM WHERE TDRM_uid = $ID");
$array = mysqli_fetch_assoc($result);
$vend = $array['TDRM_Vendor'];    
$po = $array['TDRM_PO_Number'];
$dateCode = $array['TDRM_Date_Code'];
$program = $array['TDRM_Program'];
$itemID = $array['TDRM_Item_ID'];
$scdNo = $array['TDRM_SCD_No'];
$file = $array['TDRM_file'];
$mir = $array['TDRM_mir'];
$status = "pending";

$output = "
    <div class='titleRow'>
        <p>New Test Data Review Sheet</p>
        <button onclick='save()' class='mainBtn'>save data</button>
        <button onclick='back()' class='mainBtn'>back</button>
    </div>
        <table class='table2' id='table2'>
            <tr>
                <th>Item ID</th>
                <th>Vendor</th>
                <th>PO Number</th>
                <th>Date Code</th>
                <th>Program</th>
                <th>SCD Number</th>
                <th>Status</th>
                <th>File</th>
                <th>MIR</th>
                <th>Created By</th>
                <th>Signed Off</th>
                <th class='tdMin'></th>
            </tr>
            <tr id='nextItem'>
                <td class='inputTD'><textarea class='desc dataInput' rows='2' cols='30' id='itemID'>$itemID</textarea></td>
                <td class='inputTD'><textarea class='desc dataInput' rows='2' cols='30' id='vend'>$vend</textarea></td>
                <td class='inputTD'><textarea class='desc dataInput' rows='2' cols='30' id='po'>$po</textarea></td>
                <td class='inputTD'><textarea class='desc dataInput' rows='2' cols='30' id='dateCode'>$dateCode</textarea></td>
                <td class='inputTD'><textarea class='desc dataInput' rows='2' cols='30' id='program'>$program</textarea></td>
                <td class='inputTD'><textarea class='desc dataInput' rows='2' cols='30' id='scdNo'>$scdNo</textarea></td>
                <td>$status</td>
                <td class='inputTD'><textarea class='desc dataInput' rows='2' cols='30' id='file'>$file</textarea></td>
                <td class='inputTD'><textarea class='desc dataInput' rows='2' cols='30' id='mir'>$mir</textarea></td
                <td>$user</td>
            </tr> 
        </table>
        <input type='hidden' id='storeID' value =$ID>
    ";
echo $output;
?>