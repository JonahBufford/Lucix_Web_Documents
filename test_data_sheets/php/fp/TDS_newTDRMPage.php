<?php

require_once 'connections.php';

$creator = $_POST['creator'];

$query = "SELECT Program
		  FROM tblCustomers
          WHERE Available = 'True'";

$result = odbc_exec ( $connOLTP, $query );

// check to see if there are any results

$progList = "";

if (odbc_num_rows ( $result ) > 0) { // start if there is a result
                                     
	// open the pending step(s)
                                     
	// check to see what type of step is about the be opened
	
    $progList = "<select id='program' class='mainSelect'>
                    <option value='' selected>Select</option>";

    $progArray = array();
	$counter = 0;
	while ( odbc_fetch_row ( $result ) ) {
        $progArray[$counter] = "<option value='" . odbc_result( $result, 1 ) . "'>" . odbc_result ( $result, 1 ) . "</option>";
        $counter++;
    }
    
    sort($progArray);

    $iterator = 0;
    while($iterator < $counter){
        $progList .= $progArray[$iterator];
        $iterator++;
    }
    
	$progList .= "</select>";
}

echo "
    <div class='titleRow'>
        <p>New Test Data Review Sheet</p>
        <button onclick='createNewSheet()' class='mainBtn'>Save Data</button>
        <button onclick='back()' class='mainBtn'>Back</button>
    </div>
    <table class='table2' id='table2'>
        <tr>
            <th>Item ID</th>
            <th>Vendor</th>
            <th>PO Number</th>
            <th>Date Code</th>
            <th>Program</th>
            <th>SCD Number</th>
            <th>MIR</th>
            <th>Created By</th>
        </tr>
        <tr id='nextItem'>
            <td class='inputTD'><input class='dash' id='newItemID'></input></td>
            <td class='inputTD'><input class='dash' id='newVend'></input></td>
            <td class='inputTD'><input class='dash' id='newPo'></input></td>
            <td class='inputTD'><input class='dash' id='newDateCode'></input></td>
            <td class='inputTD'>$progList</td>
            <td class='inputTD'><input class='dash' id='newscdNo'></input></td>
            <td class='inputTD'><input class='dash' id='newmir'></input></td>
            <td>$creator</td>
            <input type='hidden' value='$creator' id='creator' >
        </tr> 
    </table>
    ";

?>