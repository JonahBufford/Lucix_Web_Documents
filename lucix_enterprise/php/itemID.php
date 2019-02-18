<?php

session_start ();


// define variables for common files

$date = date ( "Y-m-d" );

$title = "Item ID Management";
$thisFileName = "itemID.php";
$_SESSION ['anchorPage'] = $thisFileName;
$useCSS = "testQual.css";
$pageTitle = "Item ID Management";
$scriptRef = 'script src="../js/defaultJS.js"></script';
$scriptRef2 = 'script src="../js/defaultJQuery.js"></script';

// import common files
require_once 'fp/iconData.php';
require_once 'fp/header.php';
require_once 'fp/connections.php';

if (isset ( $_SESSION ['username'] )) {
    
    $userID = $_SESSION ['username'];

    $qtySelect = "<select id='itemQty' class='mainSelect'>
                <option disabled selected>#</option>
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>
                <option>7</option>
                <option>8</option>
                <option>9</option>
                <option>10</option>
                <option>11</option>
                <option>12</option>
                <option>13</option>
                <option>14</option>
                <option>15</option>
                <option>16</option>
                <option>17</option>
                <option>18</option>
                <option>19</option>
                <option>20</option>
            </select>";

$dwgList = "";

$file = "//cmdatastorage/Documentation/Released/211821";

$file = strtoupper($file);

foreach ( glob ( "$file"."*.[pP][dD][fF]" ) as $filename ) {
    $dwgList .= "<a href='fp/openDwg.php?dwg=" . $filename . "'target='_blank'>Item Naming Convention - 211821</a>";
}


echo <<<_FixedHTML

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/default.css">
    <link rel="stylesheet" type="text/css" href="../css/itemID.css">
    <script src="../js/defaultJS.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/defaultJQuery.js"></script>
    <script src="../js/jquery.tablesorter.js"></script>
    <title>$pageTitle</title>
</head>
<body>
    $header
    <div id='container'>
        <div id='content'>
            <div id='adminarea' onclick='adminFunctions()'>admin</div>
            <div class='holderDiv'>
                <table class='table1'>
                    <tr>
                        <td class='centered statusButtons' rowspan='2'>
                            My Item IDs by Status<br><br>
                            <button onclick='confByStatus("Reserved")' class='mainBtn'>Reserved</button>
                            <button onclick='confByStatus("Ready")' class='mainBtn'>Ready</button>
                            <button onclick='confByStatus("In ERP")' class='mainBtn'>In ERP</button>
                            <button onclick='confByStatus("Archived")' class='mainBtn'>Archived</button>
                            <button onclick='confByStatus("Recent 100")' class='mainBtn'>Recent</button>
                        </td>
                        <td class='spacerTd'></td>
                        <td>$qtySelect</td>
                        <td><button onclick='getNumbers()' class='mainBtn'>Reserve</button></td>
                        <td class='explain' >Reserve Item ID(s)</td>
                    </tr>
                    <tr>
                        <td class='spacerTd'></td>
                        <td><input class='item' type='text' placeholder='Base #' id='baseID' class='baseBox'></td>
                        <td><button onclick='configure(0)' class='mainBtn'>Configure</button></td>
                        <td class='explain'>Find My Item IDs by Item ID</td>
                    </tr>
                </table>
                <div class='naming'>$dwgList</div>
                <div id='message'>message</div>
                <div id='mUpdate'>
                </div>
                <input type='hidden' id='dataSta' value='0'>
                <input type='hidden' id='dashFlag' value='0'>
                <input type='hidden' id='storeItem' value='0'>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function(){	
            $('#container').on('click', '#pClose', function(){
                $("#detailDiv").hide();
            });
        });

        function closeItem(rowID, pBase, pDash ){

            //change status to In ERP, move dwg files to entered
            var staField = "#sta" + rowID;
            
            $(staField).html("In ERP");

            if (!pDash){
                pDash = "";
            }

            $.ajax({
                type: 'POST',
                url: 'fp/itemComp.php',
                dataType: 'html',
                data: {
                    rowID: rowID,
                    pBase: pBase,
                    pDash: pDash
                },
                success: function(html){
                    
                },

            });

        }

        function adminFunctions(){
            
            var code1 = prompt("Code");

            if (code1 == "123") {

                $.ajax({
                    type: 'POST',
                    url: 'fp/itemAdmin.php',
                    dataType: 'html',
                    data: {
                        searchTerm: 'Ready'
                    },
                    success: function(html){
                        document.getElementById('mUpdate').innerHTML = html;
                        $("#table2").tablesorter(
                            {
                            sortInitialOrder: "desc",
                            selectorHeaders: 'thead th.sortable'
                            }
                        );
                    },

                });

            } else {
                alert("no.");
            }
        }

        function back(){

            var dataSta = $('#dataSta').val();

            if (dataSta == '1'){

                if (confirm('You have unsaved changes.  Do you still want to leave the page?')) {
                    // Save it!
                    $('#dataSta').val('0');

                } else { return; }
            } 

            $.ajax({
                type: 'POST',
                url: 'fp/itemHome.php',
                dataType: 'html',
                success: function(html){
                    document.getElementById('mUpdate').innerHTML = html;
                },
            });

        }

        function showUpBtn(){
            $('#upBtn').show();
        }

        function configure(confTerm){

            $("#storeItem").val(confTerm);

            if (confTerm == 0){
                var baseNum = $("#baseID").val();
                
                var n = baseNum.length;
                
                if ( n < 4 ) {
                    message("Enter at least 4 digits of the base number.");
                    $("#baseID").focus()
                    return;
                }
                
                if ( n > 6 ) {
                    message("Enter at the base number only.  No suffix.");
                    $("#baseID").focus()
                    return;
                }

            } else {
                var baseNum = confTerm;
            }

            $.ajax({
                type: 'POST',
                url: 'fp/confItem.php',
                dataType: 'html',
                data: {
                    itemID: baseNum
                },
                success: function(html){
                    document.getElementById('mUpdate').innerHTML = html;
                    $("#table2").tablesorter(
                        {
                        sortInitialOrder: "desc",
                        selectorHeaders: 'thead th.sortable'
                        }
                    );
                },

            });

        }

        function confByStatus(specSta){
            var thisSta = "z" + specSta;
            configure(thisSta);
        }

        function getNumbers(){
            
            var qty = $("#itemQty").val()

            $.ajax({
                type: 'POST',
                url: 'fp/reserveItem.php',
                dataType: 'html',
                data: {
                    itemQty: qty
                },
                success: function(html){
                    configure("y" + html);
                },

            });


        }

        $(document).ready(function(){	
            $('#container').on('change', '.dataInput', function(){
                saveDataNoUpdate();
            });
        });

        $(document).ready(function(){	
            $('#container').on('change', '.typeSelect', function(){
                
                var rowID = this.id.substring(2);
                var docImg = '#doc' + rowID;
                var nodocImg = '#nodoc' + rowID;
                
                if(this.value == "Purchased Item"){
                    $(docImg).show();
                    $(nodocImg).hide();
                } else {
                    $(docImg).hide();
                    $(nodocImg).show();
                }
            });
        });

        $(document).ready(function(){	
            $('#container').on('change', '#piPM', function(){

                if(this.value == "Purchased"){
                    $('#piAdd').show();
                } else {
                    $('#piAdd').hide();
                }
            });
        });

        $(document).ready(function(){	
            $('#container').on('change', '.infoInput', function(){
                saveP();
            });
        });

        $(document).ready(function(){	
            $('#container').on('change', '.infoSel', function(){
                saveP();
            });
        });

        $(document).ready(function(){    
            $('#container').on('change', '.statusSelect', function(){
                
                var rowID = this.id.substring(2);
                var hotImg = '#hot' + rowID;
                var coldImg = '#cold' + rowID;
                
                if(this.value == "Ready"){
                    $(hotImg).show();
                    $(coldImg).hide();
                } else {
                    $(hotImg).hide();
                    $(coldImg).show();
                }
            });
        });

        function selectSuf(tagID){

            cellIdent = 'x' + tagID; 

            var tagValue = $("#" + cellIdent).val();

            if (tagValue == 'Yes'){
                document.getElementById(cellIdent).value = "No";
                document.getElementById(tagID).style.backgroundColor = "rgb(255, 205, 205)";
            } else {
                document.getElementById(cellIdent).value = "Yes";
                document.getElementById(tagID).style.backgroundColor = "rgb(179, 255, 179)";
            }
            saveDataNoUpdate();

        }

        function undo(){
            var storedTerm = $("#storeItem").val();
            configure(storedTerm);
        }

        function saveDataNoUpdate(){

            var detailSource = document.getElementsByName("infoList[]");
            var detailArray = new Array();

            var storedTerm = $("#storeItem").val();

            Array.prototype.forEach.call(detailSource, function(el) {
                var valueToPush = new Array();
                valueToPush[0] = el.id;
                valueToPush[1] = el.value;
                detailArray.push(valueToPush);
            });
    
            $.ajax({
                type: 'POST',
                url: 'fp/updateItem.php',
                dataType: 'html',
                data: {
                    infoList: detailArray
                },
                success: function(html){
                    if (html == '1'){

                    } else {
                        alert(html);
                    }
                }
            });
        }

        function message(whatToSay){

            $('#message').html(whatToSay);
            $('#message').fadeIn();

            setTimeout(function() {
                $('#message').fadeOut();
            }, 3000); // <-- time in milliseconds

        }

        function message2(){

            $('#message').html("Sending email...");
            $('#message').fadeIn();

        }

        function addNewDash(itemID, type1){

            var baseNum = itemID;
            var thisType = type1;

            var storedTerm = $("#storeItem").val();

            $.ajax({
                type: 'POST',
                url: 'fp/reserveItemDash.php',
                dataType: 'html',
                data: {
                    baseNum: baseNum,
                    thisType: thisType
                },
                success: function(html){
                    if (html == '1'){
                        configure(storedTerm);
                    } else {
                        alert(html);
                    }
                    $('#dataSta').val('0');
                },
                error: function(response) {
                    alert(response);
                }
            });
        }

        $("#content").on("keyup", "#baseID", function(e) {
            if (e.which == 13) {
                configure(0);
            }
        });

        function deleteItem(rowID){

            if (confirm('Delete this item?  You can archive it instead.')) {

                var storedTerm = $("#storeItem").val();
                
                $.ajax({
                    type: 'POST',
                    url: 'fp/deleteItem.php',
                    dataType: 'html',
                    data: {
                        rowID: rowID
                    },
                    success: function(html){
                        if (html == '1'){
                    
                            message("Item Deleted");
                            configure(storedTerm);

                        } else {

                        alert(html);
                        }
                    }
                });


            } else { return; }
        }

        function docNotes(rowID){
            
            $("#purRowID").val(rowID);

            saveDataNoUpdate();

            $.ajax({
                type: 'POST',
                url: 'fp/itemPur.php',
                dataType: 'html',
                data: {
                    rowID: rowID
                },
                success: function(html){
                    document.getElementById('pInfo').innerHTML = html;
                    $("#detailDiv").show();
                }
            });
        }

        function docUpdate(){
            var existDoc = $('#pDoc').html();
            var newDoc = $('#fileToUpload').val();

            var slash = "\\\";

            var n = newDoc.lastIndexOf(slash);

            newDoc = newDoc.substring(n + 1);
            
            if (existDoc != ""){
                existDoc = existDoc + ", "
            }

            docString = existDoc + newDoc;
            
            $('#pDoc').html(docString);

            message(docString);

            $('#fileUpload').hide();
            
            saveDataNoUpdate();

        }

        function hotNotify(rowID){

            var storedTerm = $("#storeItem").val();

            if (confirm("Send email to process immediately?")) {

                message2();
               
                $.ajax({
                    type: 'POST',
                    url: 'fp/hotEmail.php',
                    dataType: 'html',
                    data: {
                        rowID: rowID
                    },
                    success: function(html){
                        if (html == '1'){
                            saveDataNoUpdate();
                            message("email sent.");
                            configure(storedTerm);
                        } else {
                            alert(html);
                        }
                    }
                });

            } else {
                
            }

        }

        function saveP(){

            var rowID = $("#purRowID").val();
            var piPM = $("#piPM").val();
            var pVen = $("#pVen").val();
            var pPN = $("#pPN").val();
            var pDoc = $("#pDoc").val();
            var pExD = $("#pExD").val();

            $.ajax({
                type: 'POST',
                url: 'fp/itemPurSave.php',
                dataType: 'html',
                data: {
                    rowID: rowID,
                    piPM: piPM,
                    pVen: pVen,
                    pPN: pPN,
                    pDoc: pDoc,
                    pExD: pExD
                },
                success: function(html){

                }
            });

        }

        function uploadDoc(rowID){
            $('#fileUpload').toggle();
        }

        confByStatus("Recent 100");



    </script>
</body>
</html>

_FixedHTML;

} else {

echo <<<_FixedHTML2

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/default.css">
    <link rel="stylesheet" type="text/css" href="../css/itemID.css">
    <script src="../js/defaultJS.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <title>$pageTitle</title>
</head>
<body>
    $header
    <div id='container'>
        <div id='content'>
            <div class='holderDiv'>
                <div class='titleRow'>
                    Please <a href='authenticate.php'>log-in</a> to use this function.
                </div>
            </div>
        </div>
    </div>
    </body>
</html>

_FixedHTML2;

}


?>