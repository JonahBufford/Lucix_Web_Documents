<?php
// authenticate.php

// initiate the session (must be the first statement in the document
session_start ();

// file and style sheet identification
$thisFileName = "APR_authenticate.php";
$useCSS = "default.css";
$functionName = "Log In Screen";

// define variables for common files
$title = "Log In";
$un_temp = "";

// import common files
require_once 'fp/iconData.php';
require_once 'fp/header.php';
require_once 'fp/connections.php';

if (isset ( $_SESSION ['anchorPage'] )) {
	
	$returnPage = $_SESSION ['anchorPage'];
} else {
	$returnPage = "APR_approvalApplication.php";
}

if (isset ( $_SESSION ['username'] )) {
	// there is an already runnign session and the us er is here to log-out
	session_unset ();
	session_destroy ();
	unset ( $_COOKIE ['username'] );
	setcookie ( 'username', null, - 1, '/' );
	
	echo '<script>alert("You have been logged out.");</script>';
	
	echo '<script>window.location = "' . $returnPage . '";</script>';
}

// abort on connection error

if ($connOLTP) {
	
	// if the username and password have been submited
	
	if (isset ( $_POST ['userNameF'] ) && isset ( $_POST ['passWordF'] )) {
		$un_temp = $_POST ['userNameF'];
		$pw_temp = $_POST ['passWordF'];
		$failMessage = "Invalid Username/Password combination";
		
		// $query = "SELECT * FROM tbl_EMP WHERE emp_name='$un_temp'";
		$query = "SELECT * FROM tblEmployees WHERE strEmpName='$un_temp'";
		$result = odbc_exec ( $connOLTP, $query );
		
		if (odbc_fetch_row ( $result ) > 0) { // check to see if there are any matches
			
			if (strtoupper($pw_temp) == strtoupper(odbc_result ( $result, 4 ))) {
				
				$_SESSION ['username'] = $un_temp;
				$_SESSION ['oltpPerm'] = odbc_result ( $result, 5 );
				$_SESSION ['userID'] = odbc_result ( $result, 1 );
				// $_SESSION ['coltpPerm'] = odbc_result ( $result, 7 );
				// $_SESSION ['ecoPerm'] = odbc_result ( $result, 8 );
				// $_SESSION ['woPerm'] = odbc_result ( $result, 9 );
				
				// setcookie ( "username", $_SESSION ['username'], time () + (86400 * 30), "/" );
				
				// echo '<script>alert("You will remain logged in for 30 days.");</script>';
				echo '<script>alert("Welcome ' . $un_temp . '.");</script>';
				echo '<script>window.location = "' . $returnPage . '";</script>';
			} 

			else {
				
				echo '<script>alert("Invalid Username/Password");</script>';
				unset ( $_POST ['userNameF'] );
				unset ( $_POST ['passWordF'] );
			}
		} else {
			
			echo '<script>alert("Invalid Username/Password");</script>';
			;
			unset ( $_POST ['userNameF'] );
			unset ( $_POST ['passWordF'] );
		}
	}
	
	// POST NOT SET. Display Login-info
} else {
	
	echo "odbc not connected";
}

// build list of Log In names for drop down

/*
 * $query = "SELECT emp_name
 * FROM tbl_EMP
 * WHERE (((emp_status)=1))
 * ORDER BY emp_name";
 */

$query = "SELECT strEmpName
					  FROM tblEmployees
					  WHERE strAccess <> 'INACTIVE'
					  ORDER BY strEmpName";

// store the results in an array
$result = odbc_exec ( $connOLTP, $query );

// check to see if there are any results

if (odbc_num_rows ( $result ) > 0) { // start if there is a result
                                     
	// open the pending step(s)
                                     
	// check to see what type of step is about the be opened
	
	$empList = "<select id='Emp' class='norm' name='userNameF'><option>Select</option>";
	
	while ( odbc_fetch_row ( $result ) ) {
		
		if (odbc_result ( $result, 1 ) == $un_temp) {
			
			$empList .= "<option value='" . odbc_result ( $result, 1 ) . "' selected='selected'>" . odbc_result ( $result, 1 ) . "</option>";
			
		} else {
			
			$empList .= "<option value='" . odbc_result ( $result, 1 ) . "'>" . odbc_result ( $result, 1 ) . "</option>";
		}
	}
	
	$empList .= "</select>";
}

// the login form

echo <<<_END

<html>
<head>
    <title>Lucix Enterprise Software</title>	
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
	<META HTTP-EQUIV="Expires" CONTENT="-1">
    <link rel="stylesheet" type="text/css" href="../css/home.css">
    <link rel="stylesheet" type="text/css" href="../css/authenticate.css">
    <link rel="stylesheet" type="text/css" href="../css/default.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
</head>
	<body>
		<div id="login">
			<form name="LogIn" method="post" action="$thisFileName">
				<table id="liTable">
					<tr>
						<td>User Name</td><td>$empList</td>
					</tr>
					<tr>
						<td>Password</td><td><input type="password" name="passWordF" size="10" class="text1" required='required'></td>
					</tr>
						<tr>
						<td><input type="submit" value="Log In" class="button"></td><td></td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>
 
_END;

?>