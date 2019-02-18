<?php

require_once 'connections.php';

if(isset($_POST['selVal'])){
    $selectedValue = $_POST['selVal'];
    $ID = $_POST['selID'];

    $query = "UPDATE QCI
            SET QCI_prog = '$selectedValue'
            WHERE QCI_uid = '$ID'";

    odbc_exec($connENT,$query);

    echo 0;

} else {
    echo "Post not set.";
}

?>