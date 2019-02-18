<?php

session_start ();

require_once 'connections.php';


$ID = $_POST['TDRMID'];

$query = "SELECT strEmpName
					  FROM tblEmployees
					  WHERE strAccess <> 'INACTIVE'
					  ORDER BY strEmpName";

// store the results in an array
$result = odbc_exec ( $connOLTP, $query );

// check to see if there are any results

$employees = "";

if (odbc_num_rows ( $result ) > 0) { // start if there is a result
                                     
	// open the pending step(s)
                                     
	// check to see what type of step is about the be opened
	
	$employees = "<select id='reviewerName' class='norm' class='mainSelect'><option value='select'>Select</option>";
	
	while ( odbc_fetch_row ( $result ) ) {
        $employees .= "<option value='" . odbc_result ( $result, 1 ) . "'>" . odbc_result ( $result, 1 ) . "</option>";
	}
	
	$employees .= "</select>";
}

echo <<< _FixedHTML
    <html>
        <body>
            <p>Name of Reviewer $employees</p>
            <p>Role of Reviewer 
                <select id='reviewerRole'>
                    <option value='select' selected>Select</option>
                    <option value='Component Engineer'>Component Engineer</option>
                    <option value='REA/Engineer'>REA/Engineer</option>
                    <option value='Quality'>Quality</option>
                    <option value='other'>Specify Other</option>
                </select>
            </p>
            <p>
                <input type='checkbox' id='notify' value='y'>
                <label for='notify'>Notify reviewer</label>
            </p>
            <button onclick='saveNewReviewer($ID)'>Add Reviewer</button>
            <button onclick='cancel($ID)'>Cancel</button>
        </body>
    </html>
_FixedHTML;

?>