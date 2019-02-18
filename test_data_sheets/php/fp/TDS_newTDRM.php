<?php

require_once 'connections.php';

$vend = $_POST ['vend'];
$po = $_POST ['po'];
$dateCode = $_POST ['dateCode'];
$program = $_POST ['program'];
$itemID = $_POST ['itemID'];
$scdNo = $_POST ['scdNo'];
$mir = $_POST ['mir'];
$creator = $_POST['creator'];

$date = date( 'Y-m-d' );

$descQuery = "SELECT IMA.IMA_ItemName
                FROM
                iERP87_Prod.dbo.IMA IMA
                WHERE IMA.IMA_ItemID = '$itemID'";

$descResult = odbc_exec($connERP,$descQuery);

$desc = '';

if (odbc_num_rows ( $descResult ) > 0) {
	
	while ( odbc_fetch_row ( $descResult ) ) {
        $desc =  odbc_result ( $descResult, 1 );
    }
}

$newItem = 0;

$sql = "INSERT INTO TDRM(TDRM_created_by, TDRM_Vendor, TDRM_PO_Number, TDRM_Date_Code, TDRM_Program, TDRM_Item_ID, TDRM_SCD_No,
        TDRM_mir, TDRM_Status, TDRM_date, TDRM_desc) 
        VALUES ('$creator', '$vend','$po','$dateCode','$program','$itemID','$scdNo','$mir','Active', '$date', '$desc')";
$result = mysqli_query($conn,$sql);

$query = "SELECT TDRM_uid FROM TDRM ORDER BY TDRM_uid DESC LIMIT 1";
$ID = mysqli_query( $conn, $query );

if($ID){
    if (mysqli_num_rows ( $ID ) > 0) {

        $row = mysqli_fetch_assoc($ID);
        $newItem = $row['TDRM_uid'];

    }
}

echo $newItem;
?>