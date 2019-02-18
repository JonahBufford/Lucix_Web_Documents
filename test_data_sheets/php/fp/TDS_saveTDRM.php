<?php

require_once 'connections.php';

if(isset($_POST['vend'])){
$vend = $_POST ['vend'];
$po = $_POST ['po'];
$dateCode = $_POST ['dateCode'];
$program = $_POST ['program'];
$itemID = $_POST ['itemID'];
$scdNo = $_POST ['scdNo'];
$mir = $_POST ['mir'];
$tdrmID = $_POST ['tdrmID'];

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

$sql = "UPDATE TDRM 
        SET TDRM_Vendor = '$vend', TDRM_PO_Number = '$po', TDRM_Program = '$program', TDRM_Date_Code = '$dateCode', TDRM_Item_ID = '$itemID',
        TDRM_SCD_No = '$scdNo', TDRM_mir = '$mir', TDRM_desc = '$desc'
        WHERE TDRM_uid = $tdrmID";

$result = mysqli_query($conn,$sql);

echo 1;
}
else{
    echo 0;
}

?>