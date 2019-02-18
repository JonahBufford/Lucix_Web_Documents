<?php

require_once 'connections.php';

$dept = $_POST['dept'];

if($dept != "all"){
    $where = "AND emp_dept = '$dept'";
}

else{
    $where = "";
}

$query = "SELECT emp_name
    FROM tbl_EMP
    WHERE emp_status ='1' AND emp_locX IS NOT NULL $where
    ORDER BY emp_name";

$result = odbc_exec ( $connENT, $query );

// check to see if there are any results

if (odbc_num_rows ( $result ) > 0) {

    $employeeList = "<select onchange='empSelected()' id='empSelect'>
                        <option value=''>Select</option>";

    while(odbc_fetch_row($result)){
        $name = odbc_result($result, "emp_name");

        $employeeList .= "<option value='$name'>$name</option>";
    }

    $employeeList .= "</select>";

} else {

        $employeeList = "No Records found";
}

echo $employeeList;

?>