<?php

require_once 'connections.php';

$ID = $_POST['TDRMID'];

$result = mysqli_query($conn, "SELECT * FROM TDRM WHERE TDRM_uid = $ID");
$array = mysqli_fetch_assoc($result);
$vend = $array['TDRM_Vendor'];    
$po = $array['TDRM_PO_Number'];
$dateCode = $array['TDRM_Date_Code'];
$program = $array['TDRM_Program'];
$itemID = $array['TDRM_Item_ID'];
$scdNo = $array['TDRM_SCD_No'];
$file = $array['TDRM_file'];
$mir = $array['TDRM_mir'];

echo <<< _FixedHTML
    <html>
        <body>
            <div>$ID</div>
            <div>
                <p>Vendor: "$vend"</p>
            </div>
            <div>
                <p>PO Number: "$po"</p>
            </div>
            <div>
                <p>Date Code: "$dateCode"</p>
            </div>
            <div>
                <p>Program: "$program"</p>
            </div>
            <div>
                <p>Item ID: "$itemID"</p>
            </div>
            <div>
                <p>SCD Number: "$scdNo" </p>
            </div>
            <div>
                <p>File: "$file"</p>
            </div>
            <div>
                <p>MIR: "$mir"</p>
            </div>
            <input type="hidden" id="storeID" value =$ID>
        </body>
    </html>
_FixedHTML;

?>