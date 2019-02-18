<?php

require_once 'connections.php';

$id = $_POST['id'];

$query = "UPDATE QCI
        SET QCI_status = 'Archived'
        WHERE QCI_uid = '$id'";

if (odbc_exec($connENT,$query)){

    echo "0";

} else {
    echo "Could not remove SN.";
}



?>