<?php

require_once 'connections.php';

$xoffset = -2;
$yoffset = -6;

$name = $_POST['name'];

$query = "SELECT emp_id, emp_name, emp_email, emp_dept, emp_locX, emp_locY, emp_phone
    FROM tbl_EMP
    WHERE emp_name = '$name'";

$result = odbc_exec ( $connENT, $query );

if (odbc_num_rows ( $result ) > 0) {

    while(odbc_fetch_row($result)){

        $id = odbc_result($result, "emp_id");
        $empX = odbc_result($result, "emp_locX");
        $empY = odbc_result($result, "emp_locY");
        $email = odbc_result($result, "emp_email");
        $phone = odbc_result($result, "emp_phone");
        $dept = odbc_result($result, "emp_dept");

        $empX = $empX + $xoffset;
        $empY = $empY + $yoffset;

        $empX = $empX . "%";
        $empY = $empY . "%";

        $emailLink = "mailto:" . $email;

        $output = "<table class='empTable'>
                        <tr>
                            <th>Employee Name</th>
                            <td>$name</td>
                        </tr>
                        <tr>
                            <th>Extension</th>
                            <td>$phone</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><a href='$emailLink' style='color:blue'>$email</a></td>
                        </tr>
                        <tr>
                            <th>Department</th>
                            <td>$dept</td>
                        </tr>
                    </table>
                    <input type='hidden' id='xPos' value='$empX'>
                    <input type='hidden' id='yPos' value='$empY'>";
    }

} else {

        $output = "";
}

echo $output;

?>