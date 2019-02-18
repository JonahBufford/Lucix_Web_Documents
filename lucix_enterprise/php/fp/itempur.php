<?php


session_start ();

$user = $_SESSION ['username'];

// import common files
require_once 'connections.php';

if (isset ( $_POST ['rowID'] )) {

    $rowID =  $_POST ['rowID'];

    //retrieve exiting purchasing data from dB
    $query = "SELECT *
                FROM ITEM
                WHERE ITM_uid = '$rowID' AND ITM_OWN = '$user'";

    $result = odbc_exec ( $connENT, $query );

    if (odbc_num_rows ( $result ) > 0) {
        
        while(odbc_fetch_row($result)){

            $pOrM = odbc_result($result,"ITM_PORM");
            $pVen = odbc_result($result,"ITM_PVEN");
            $pPN = odbc_result($result,"ITM_PPN");
            $pDoc = odbc_result($result,"ITM_PDOC");
            $pExD = odbc_result($result,"ITM_PEXD");
            $pBase = odbc_result($result,"ITM_ID");
            $pDash = odbc_result($result,"ITM_DASH");
        }
    }

    $selMfg = "";
    $selPur = "";
    $showMore = "hidden";

    $pOrM = trim($pOrM);

    if (strcmp($pOrM,"Manufactured") == 0){
        $selMfg = "selected";
    } else {
        $selPur = "selected";
        $showMore = "";
    }

    if ($pDash == ""){
        $itemRemind = $pBase;
    } else {
        $itemRemind = $pBase . "-" . $pDash;
    }

    $output = "<div class='emphText'>$itemRemind</div>
                <br>
                <select id='piPM' class='infoSel'>
                    <option $selMfg>Manufactured</option>
                    <option $selPur>Purchased</option>
                </select>
                <input type='hidden' id='purRowID' value='$rowID'>
                <br><br>
                <div id='piAdd' $showMore>
                    <div class='label1'>Vendor</div>
                    <input type='text' class='infoInput' value='$pVen' id='pVen'>
                    <div class='label1'>Mfg Part Number</div>
                    <input type='text' class='infoInput' value='$pPN' id='pPN'>
                    <div class='label1'>Specify Existing Docs</div>
                    <input type='text' class='infoInput' value='$pExD' id='pExD'>
                    <div class='label1'>Uploaded Document(s)</div>
                    <div class='filelist' id='pDoc'>$pDoc</div>
                    <input type='button' class='pBtn' value='add file' onclick='uploadDoc()' id='addFile'>
                    <div id='fileUpload' hidden>
                        <form action='fp/itemFileUpload.php' method='post' enctype='multipart/form-data' target='_blank'>
                            Select file to upload:
                            <br>
                            <input type='file' name='fileToUpload' id='fileToUpload' class='choseBtn' onclick='showUpBtn()'>
                            <br>
                            <input type='submit' value='Upload File' name='submit' class='pBtn' onclick='docUpdate()' id='upBtn' hidden>
                            <input type='hidden' value='$rowID' name='rowID'>
                            <input type='hidden' value='$pBase' name='pBase'>
                            <input type='hidden' value='$pDash' name='pDash'>
                        </form>
                    </div>
                </div>
                <button onclick='saveP()' class='pChanged pBtn' hidden>save data</button>
                <button onclick='docNotes($rowID)' class='pChanged pBtn' hidden>undo</button>";

    echo $output;

} else {

echo "Post not set.";

}



?>