<?php

require_once 'connections.php';

$def1 = $_POST['def1'];
$def2 = $_POST['def2'];
$def3 = $_POST['def3'];
$def4 = $_POST['def4'];

$query = "SELECT ODEF_id
            FROM ODEF
            WHERE ODEF_1 = '$def1' AND  ODEF_2 = '$def2' AND ODEF_3 = '$def3' AND ODEF_4 = '$def4'";

$result = odbc_exec($connENT,$query);

if(odbc_num_rows($result) > 0){
    $id = odbc_result($result, 'ODEF_id');
}

echo $id;

?>