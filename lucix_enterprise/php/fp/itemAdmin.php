<?php

session_start ();

$user = $_SESSION ['username'];

if (isset ( $_POST ['searchTerm'] )) {

    $searchTerm =  $_POST ['searchTerm'];

    $displayTerm = "Admin View";

// import common files
require_once 'connections.php';

    $query = "SELECT *
            FROM ITEM
            WHERE ITM_STA = '$searchTerm'
            ORDER BY ITM_ID, ITM_DASH";

    $result = odbc_exec ( $connENT, $query );

    $table = "
        <table class='table2 tablesorter' id='table2'>
            <thead>
                <tr>
                    <th class='headerSortDown sortable'>Item ID</th>
                    <th>Dash#</th>
                    <th>Description</th>
                    <th class='sortable'>Type</th>
                    <th>UofM</th>
                    <th class='sortable'>Department</th>
                    <th class='quick'>EM</th>
                    <th class='quick'>QU</th>
                    <th class='quick'>FL</th>
                    <th class='quick'>MM</th>
                    <th class='quick'>ST</th>
                    <th class='smlTxt'>Fixture</th>
                    <th class='quick'>Status</th>
                    <th class='sortable'>Owner</th>
                    <th class='tdMin'></th>
                </tr> 
            </thead>
            <tbody>
    ";

    if (odbc_num_rows ( $result ) > 0) {
        
        while(odbc_fetch_row($result)){

            $desc = odbc_result($result,"ITM_DESC");
            $recID = odbc_result($result,"ITM_uid");
            $nextItem = odbc_result($result,"ITM_ID");
            $dash = odbc_result($result,"ITM_DASH");
            $em = odbc_result($result,"ITM_SEM");
            $qu = odbc_result($result,"ITM_SQU");
            $fl = odbc_result($result,"ITM_SFL");
            $mm = odbc_result($result,"ITM_SMM");
            $st = odbc_result($result,"ITM_SST");
            $status = odbc_result($result,"ITM_STA");
            $owner = odbc_result($result,"ITM_OWN");
            $type = odbc_result($result,"ITM_TYPE");
            $fix = odbc_result($result,"ITM_FIX");
            $dept = odbc_result($result,"ITM_DEPT");
            $uom = odbc_result($result,"ITM_UM");
            $hotOk = odbc_result($result,"ITM_HOT");

            $status = trim($status);
            $type = trim($type);
            $uom = trim($uom);

            $dash = trim($dash);

            if (strlen($dash) <= 0){
                $baseItemClass = 'baseNumStyle';
               // $canAddDash = '';
            } else {
                $baseItemClass = 'dashNumStyle';
               // $canDelete = "";
            }

            $emVal = 'No';
            $quVal = 'No';
            $flVal = 'No';
            $mmVal = 'No';
            $stVal = 'No';
            $fxVal = 'No';

            $emSel = 'noSelect';
            $quSel = 'noSelect';
            $flSel = 'noSelect';
            $mmSel = 'noSelect';
            $stSel = 'noSelect';
            $fxSel = 'noSelect';

            if ($em == 1){
                $emVal = 'Yes';
                $emSel = 'yesSelect';
            }
            if ($qu == 1){
                $quVal = 'Yes';
                $quSel = 'yesSelect';
            }
            if ($fl == 1){
                $flVal = 'Yes';
                $flSel = 'yesSelect';
            }
            if ($mm == 1){
                $mmVal = 'Yes';
                $mmSel = 'yesSelect';
            }
            if ($st == 1){
                $stVal = 'Yes';
                $stSel = 'yesSelect';
            }
            if ($fix == 1){
                $fxVal = 'Yes';
                $fxSel = 'yesSelect';
            }

                $table .= "
                    <tr id='nextItem'>
                        <td class='$baseItemClass'>$nextItem</td>
                        <td>$dash</td>
                        <td class='inputTD'>$desc</td>
                        <td>$type</td>
                        <td>$uom</td>
                        <td>$dept</td>
                        <td class='sufCell $emSel'>EM</td>
                        <td class='sufCell $quSel'>QU</td>
                        <td class='sufCell $flSel'>FL</td>
                        <td class='sufCell $mmSel'>MM</td>
                        <td class='sufCell $stSel'>ST</td>
                        <td class='sufCell $fxSel'>FIX</td>
                        <td class='inputTD centered' id='sta$recID'>$status</td>
                        <td>$owner</td>
                        <td><button class='delBtn' onclick='closeItem($recID, $nextItem, $dash)'>Mark Complete</button> Deletes uploaded files</td>
                    </tr>
                        ";

        }

        $table .= "</tbody></table>";

    } else {
        $displayTerm = "No matches found.";
    }

    echo "<div class='titleRow'>
        $displayTerm
        <br>
    </div>";


    echo $table;

} else {
    echo "Post not set.  Error.";
}

?>