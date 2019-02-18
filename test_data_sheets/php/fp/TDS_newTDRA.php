<?php

session_start ();

require_once 'connections.php';
$TDRMID = $_POST['TDRMID'];
$name = $_POST['name'];
$role = $_POST['role'];
$user = $_SESSION['username'];

$query = "SELECT *
          FROM TDRA
          WHERE TDRA_assign = '$name' AND TDRA_sheetID = '$TDRMID'";
$check = mysqli_query($conn,$query);

$i = mysqli_fetch_assoc($check);
if($i['TDRA_assign'] == $name){
        echo 'x';
}
else{
        $sql = "INSERT INTO TDRA (TDRA_assign, TDRA_role, TDRA_sheetID, TDRA_disp, TDRA_addedBy)
                VALUES ('$name', '$role', '$TDRMID', 'Pending', '$user')";
        $result = mysqli_query($conn,$sql);
        
        echo $TDRMID;
}

?>