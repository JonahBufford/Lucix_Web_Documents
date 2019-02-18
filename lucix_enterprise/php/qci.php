<?php

session_start();


$title = "QCI Tracking";
$thisFileName = "qci.php";
$_SESSION ['anchorPage'] = $thisFileName;
$pageTitle = "QCI Tracking";

$user = '';       

if(isset($_SESSION['username'])){
    $user = $_SESSION['username'];
}

// import common files
require_once 'fp/iconData.php';
require_once 'fp/header.php';
require_once 'fp/connections.php';

//build program selection

$programSel = "<select class='qciSelect' id='progF' onchange='buildTable2()'><option value='%' selected>All Programs</option>";
                                            
$queryProg = "SELECT tblCustomers.[Program], tblCustomers.[Active]
              FROM [Online Traveler Program Official].dbo.tblCustomers
              WHERE (tblCustomers.[Active] = 1)
              ORDER BY tblCustomers.[Program]";
    
            $resultProg = odbc_exec($connOLTP,$queryProg);

            if (odbc_num_rows ( $resultProg ) > 0) {

              //sn found
              while(odbc_fetch_row($resultProg)){

                  $program = odbc_result($resultProg,'Program');
                  $activeSta  = odbc_result($resultProg,'Active');
                  
                  $programSel .= "<option value='$program'>$program</option>";
              }

            }
            
            $programSel .= "</select>";


echo<<<_FixedHTML

<html>
    <head>
        <link rel='stylesheet' type='text/css' href='../css/qci.css'>
        <link rel="stylesheet" type="text/css" href="../css/default.css">
        <script src="../js/defaultJS.js"></script>
        <script src="../js/jquery-ui.min.js"></script>
        <script src="../js/defaultJQuery.js"></script>
        <script src="../js/jquery.tablesorter.js"></script>
    </head>
    <body>
        $header
        <div id='container'>
            <div id='workingDiv' hidden>updating</div>
            <div id='content'>
                <div class='holderDiv'>
                    <div id='mUpdate'>
                        <table class='table1'>
                            <tr>
                                <td>Add QCI By Serial Number</td>
                                <td><input class='qciInput' type='text' placeholder='Serial #' id='sn' class='baseBox'></td>
                                <td>
                                    <select class='qciSelect' id='qciType'>
                                        <option value='RGA/DPA'>RGA/DPA</option>
                                        <option value='Life Test'>Life Test</option>
                                    </select>
                                </td>
                                <td><button onclick='showStatusSN()' class='btn'>Add</button></td>

                            </tr>
                            <tr>
                                <td>Reserve Unit By Part Number</td>
                                <td><input class='qciInput' type='text' placeholder='Part #' id='pn' class='baseBox'></td>
                                <td>
                                    <select class='qciSelect' id='qciTypePN'>
                                        <option value='RGA/DPA'>RGA/DPA</option>
                                        <option value='Life Test'>Life Test</option>
                                    </select>
                                </td>
                                <td><button onclick='showStatusPN()' class='btn'>Add</button></td>
                            </tr>
                        </table>
                        <br>
                        <hr>
                            <table class='table1'>
                                <tr>
                                    <td>Filter Results</td>
                                    <td>
                                        <select class='qciSelect' id='statusF' onchange='buildTable2()'>
                                            <option value='%' selected>All Statuses</option>
                                            <option value='In Process'>In-Process Only</option>
                                            <option value='Passed'>Passed Only</option>
                                            <option value='Failed'>Failed Only</option>
                                            <option value='Pending'>Pending Only</option>
                                        </select>
                                    </td>
                                    <td><select class='qciSelect' id='typeF' onchange='buildTable2()'>
                                            <option value='%' selected>All Types</option>
                                            <option value='%RGA%'>RGA/DPA</option>
                                            <option value='%Life%'>Life Test</option>
                                        </select>
                                    </td>
                                    <td>$programSel</td>
                                    <td><button onclick='toggleNotes()' class='btn'>Toggle Notes</button><input type='hidden' value="On" id='noteTag'></td>
                                </tr>
                            </table>                                
                                
                        <div id='secondTable'></div>
                    </div>
                </div>
            </div>
        </div>
        <script>

            function addSN(id){
                var id = id;
                
                var sn = prompt("Enter Serial #");

                if (sn == null || sn == "") {
                    return;
                } 

                $.ajax({
                    type: 'POST',
                    url: 'fp/QCI_assignSN.php',
                    dataType: 'html',
                    data: {
                        sn: sn,
                        id: id
                    },
                    success: function(html) {
                        if(html == 0){
                            buildTable2();
                        }
                        else{
                            alert(html);
                        }
                    }
                });
            }

            function buildTable2() {

                $('#workingDiv').fadeIn();

                var typef = $('#typeF').val();
                var staf = $('#statusF').val();
                var notf = $('#noteTag').val(); 
                var prof = $('#progF').val();

                $.ajax({
                    type: 'POST',
                    url: 'fp/QCI_buildTable2.php',
                    dataType: 'html',
                    data: {
                        typef: typef,
                        staf: staf,
                        notf: notf,
                        prof: prof
                    },
                    success: function(html) {
                        $('#workingDiv').fadeOut();
                        document.getElementById('secondTable').innerHTML = html;
                        $("#table2").tablesorter(
                            {
                            sortInitialOrder: "desc",
                            selectorHeaders: 'thead th.sortable'
                            }
                        );
                    }
                });
                
            }

            function deselect(id) {
                if( confirm( 'Would you like to stop tracking this part?' ) ){
                    var id = id;

                    alert(id);
                    
                    $.ajax({
                        type: 'POST',
                        url: 'fp/QCI_deselect.php',
                        dataType: 'html',
                        data: {
                            id: id
                        },
                        success: function (html) {
                            if(html == 0){
                                buildTable2();
                            }
                            else{
                                alert(html);
                            }
                        }
                    });
                }

                else{
                    buildTable2()
                }
            }

            $(document).ready(function(){	
                $('#container').on('change', '.dataInput', function(){
                    saveNotes();
                });
            });

            function saveNotes() {
                var detailSource = document.getElementsByName("notes");
                var detailArray = new Array();

                Array.prototype.forEach.call(detailSource, function(el) {
                    var valueToPush = new Array();
                    valueToPush[0] = el.id;
                    valueToPush[1] = el.value;
                    detailArray.push(valueToPush);
                });

                $.ajax({
                    type: 'POST',
                    url: 'fp/QCI_saveNotes.php',
                    dataType: 'html',
                    data: {
                        commentList: detailArray
                    },
                    success: function(html){
                    }
                });
            }

            function showStatusPN() {

                if($('#pn').val() != ''){
                    var pn = $('#pn').val();
                    var qtype = $('#qciTypePN').val();

                    $.ajax({
                        type: 'POST',
                        url: 'fp/QCI_addPN.php',
                        dataType: 'html',
                        data: {
                            pn: pn,
                            qtype: qtype
                        },
                        success: function(html) {
                            if(html == 0){
                                buildTable2();
                            }
                            else{
                                alert(html);
                            }
                            document.getElementById('sn').value = '';
                        }
                    });
                }

                else{
                    alert('Please enter a serial number');
                }
            }

            function showStatusSN() {
                if($('#sn').val() != ''){
                    var sn = $('#sn').val();
                    var qtype = $('#qciType').val();

                    $.ajax({
                        type: 'POST',
                        url: 'fp/QCI_addSN.php',
                        dataType: 'html',
                        data: {
                            sn: sn,
                            qtype: qtype
                        },

                        success: function(html) {
                            if(html == 0){
                                buildTable2();
                            }
                            else{
                                alert(html);
                            }
                            document.getElementById('sn').value = '';
                        }
                    });
                }

                else{
                    alert('Please enter a serial number');
                }
            }

            function saveStatus(thisSelect, thisID){
                $.ajax({
                    type: 'POST',
                    url: 'fp/QCI_saveStatus.php',
                    dataType: 'html',
                    data: {
                        selVal: thisSelect.value,
                        selID: thisID
                    },
                    success: function(html){
                    }
                });
            }

            function saveProg(thisSelect, thisID){
                $.ajax({
                    type: 'POST',
                    url: 'fp/QCI_saveProg.php',
                    dataType: 'html',
                    data: {
                        selVal: thisSelect.value,
                        selID: thisID
                    },
                    success: function(html){
                    }
                });
            }

            function toggleNotes() {

                
                var currentNote = $('#noteTag').val()
                
                if (currentNote == "On") {
            
                    $('#noteTag').val("Off");

                } else {

                    $('#noteTag').val("On");
                }
                
                buildTable2();
            }

            buildTable2();

        </script>
    </body>
</html>

_FixedHTML;

?>