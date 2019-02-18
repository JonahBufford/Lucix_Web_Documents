<?php

session_start ();

require_once 'connections.php';
$newItem = 0;

$sql = "INSERT INTO TDRM(TDRM_created_by) 
        VALUES ('Username')";
$result = mysqli_query($conn,$sql);

$query = "SELECT TDRM_uid FROM TDRM ORDER BY TDRM_uid DESC LIMIT 1";
$ID = mysqli_query( $conn, $query );

if($ID){
    if (mysqli_num_rows ( $ID ) > 0) {

        $row = mysqli_fetch_assoc($ID);
        $newItem = $row['TDRM_uid'];

    };
}

echo $newItem;
?>
