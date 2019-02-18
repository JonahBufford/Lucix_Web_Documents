<?php

require_once 'connections.php';

$pn = $_POST['pn'];
$type = $_POST['qtype'];

$query = "INSERT INTO QCI (QCI_serial, QCI_pn, QCI_type)
          VALUES ( 'TBD', '$pn', '$type')";

odbc_exec($connENT,$query);

?>