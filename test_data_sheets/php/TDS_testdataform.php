<?php

session_start ();

require_once 'fp/connections.php';
require_once 'fp/TDS_header.php';

if(isset($_GET['id'])){
    $addID = $_GET['id'];
    $onload = "onLoad='buildProcessingForm(\"$addID\")'";
}
else{
    $onload = "onLoad='buildTable2()'";
}

$_SESSION['admin'] = array("Jonah Bufford");
$admin = $_SESSION['admin'];

$query = "SELECT strEmpName
					  FROM tblEmployees
					  WHERE strAccess <> 'INACTIVE'
                      ORDER BY strEmpName";
       
$user = '';       

if(isset($_SESSION['username'])){
    $user = $_SESSION['username'];
}

// store the results in an array
$result = odbc_exec ( $connOLTP, $query );

// check to see if there are any results

$empList = "";

if (odbc_num_rows ( $result ) > 0) { // start if there is a result
                                     
	// open the pending step(s)
                                     
	// check to see what type of step is about the be opened
	
    $empList = "<select id='empFilter' class='mainSelect' onchange='buildTable2()'>
                    <option value=\"LIKE '%'\" selected>All Employees</option>";
	
	while ( odbc_fetch_row ( $result ) ) {
        $empList .= "<option value=\"= '" . odbc_result ( $result, 1 ) . "'\">" . odbc_result ( $result, 1 ) . "</option>";
	}
    
	$empList .= "</select>";
}



echo<<<_FixedHTML

<html>
    <head>
        <link rel='stylesheet' type='text/css' href='../css/default.css'>
        <link rel='stylesheet' type='text/css' href='../css/TDS_testdataform.css'>
        <script type='text/javascript' src='../js/defaultJQuery.js'></script>
        <script>


            function addReviewer(tdrmID){
                var id = tdrmID;
                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_addReviewerForm.php',

                    dataType: 'html',

                    data: {
                        TDRMID: id
                    },

                    success: function(html) {
                        document.getElementById('mUpdate').innerHTML = html;
                    }
                });
            }

            function saveNewReviewer(tdrmID){
                tdrmID = tdrmID;
                
                if($('#reviewerName').val() == 'select' || $('#reviewerRole').val() == 'select' ){
                    alert('Please enter the name and role of the new reviewer');
                }

                else{
                    var role = "";
                    var name = $('#reviewerName').val();

                    if($('#reviewerRole').val() == 'other'){
                        role = prompt("Enter role:");
                    }

                    else{
                        role = $('#reviewerRole').val();
                    }
                    newTDRA(tdrmID, name, role );
                }
            }

            function approveTDRA(id) {
                var id = id;
                saveComments(id);
                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_approveTDRA.php',

                    dataType: 'html',

                    data: {
                        id:id
                    },

                    success: function(html) {
                        buildProcessingForm(html);
                    }
                });
            }

            function completeTDRM(id) {
                if($('#program').val() != ''){
                    var id = id;

                    saveTDRM(id);

                    $.ajax({
                        type: 'POST',
    
                        url: 'fp/TDS_completeTDRM.php',
    
                        dataType: 'html',
    
                        data: {
                            id: id
                        },
    
                        success: function(html) {
                            buildProcessingForm(html);
                        }
                    });
                }
                else{
                    alert('Please select a program');
                }
            }

            function back(){
                window.location.replace('http://localhost/test_data_sheets/php/TDS_testdataform.php');
            }

            function buildTable2() {
                var filter = $('#selectFilter').val();
                var name = $('#empFilter').val();
                var statusText = $('#selectFilter option:selected').text();
                var nameText = $('#empFilter option:selected').text();

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_buildTable2.php',

                    dataType: 'html',

                    data: {
                        filter: filter,
                        name: name,
                        statusText: statusText,
                        nameText: nameText
                    },

                    success: function(html){
                        document.getElementById('secondTable').innerHTML = html;
                    },
                });
            }

            function buildProcessingForm(ID) {
                var id = ID;

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_tdrProcessingForm.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        if(html === ""){
                            alert('ID not found');
                            back();
                        }
                        
                        else{
                            document.getElementById('mUpdate').innerHTML = html;
                        }
                    }
                });
            }

            function cancel(id){
                buildProcessingForm(id);
            }

            function createNewApprovalSheetTDRA(tdrmID){
                var baseNum = tdrmID;
                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_addReviewerForm.php',

                    dataType: 'html',

                    data: {
                        TDRMID: baseNum
                    },

                    success: function(html){
                        document.getElementById('tdraUpdate').innerHTML = html;
                    },
                });
            }

            function createNewSheet(){
                if($('#newItemID').val() != '' && $('#program').val() != '' ){
                    var vend = $('#newVend').val();
                    var po = $('#newPo').val();
                    var dateCode = $('#newDateCode').val();
                    var program = $('#program').val();
                    var itemID = $('#newItemID').val();
                    var scdNo = $('#newscdNo').val();
                    var file = $('#newfile').val();
                    var mir = $('#newmir').val();
                    var creator = $('#creator').val();
                    
                    $.ajax({
                        type: 'POST',

                        url: 'fp/TDS_newTDRM.php',

                        dataType: 'html',

                        data: {
                            vend: vend,

                            po: po,

                            dateCode: dateCode,

                            program: program,

                            itemID: itemID,

                            scdNo: scdNo,

                            file: file,

                            mir: mir,

                            creator: creator
                        },

                        success: function(html){

                            newTDRA(html,'Deborah Taylor','Component Engineer');
                        },
                    });
                }

                if($('#newItemID').val() == ''){
                    alert('You must input an item id!');
                }
                if($('#program').val() == ''){
                    alert('You must select a program!');
                }
            }

            function deleteTDRA(id) {
                var id = id;
                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_deleteTDRA.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        if(html == 'x'){
                            alert('You must have at least one employee assigned to review');
                        }
                        else{
                            buildProcessingForm(html);
                        }
                    }
                });
            }

            function editPrev(){
                if( $('#enterID').val() != '' ){
                    var ID = $('#enterID').val();
                    if(ID == parseInt(ID,10)){
                        buildProcessingForm(ID);
                    }
                    else{
                        alert('Please input a valid integer value');
                    }
                }

                else{
                    alert('Please enter a value');
                }
            }

            function newTDRA(tdrmID, nameInput, roleInput){
                var baseNum = tdrmID;
                var name = nameInput;
                var role = roleInput;

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_newTDRA.php',

                    dataType: 'html',

                    data: {
                        TDRMID: baseNum,

                        name: name,

                        role: role,
                    },

                    success: function(html){
                        if(html == 'x'){
                            alert("Employee is already assigned as a reviewer");
                        }

                        else{
                            buildProcessingForm(html);

                            var val = $('#notify').val();

                            if( document.getElementById('notify').checked ) {
                                sendEmail(html, name);
                            }
                        }
                    },
                });
            }

            function newTDRM() {
                if('$user' != '' ){
                    var user = '$user';

                    $.ajax({
                        type: 'POST',

                        url: 'fp/TDS_newTDRMPage.php',

                        dataType: 'html',

                        data: {
                            creator: user
                        },

                        success: function(html){
                            document.getElementById('mUpdate').innerHTML = html;
                        }
                    });
                }

                else{
                    alert('You must be logged in to access this function');
                }
            }

            function rejectTDRA(id) {
                var id = id;

                saveComments(id);

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_rejectTDRA.php',

                    dataType: 'html',

                    data: {
                        id:id
                    },

                    success: function(html) {
                        buildProcessingForm(html);
                    }
                });
            }

            function resetTDRA(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_resetTDRA.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        
                        buildProcessingForm(html);
                    }
                });
            }

            function resetTDRM(id){
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_resetTDRM.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        buildProcessingForm(html);
                    }
                });
            }

            function reveal(tdraid, isAdmin) {
                
                if(isAdmin){
                    document.getElementById('saveAll').style.display = "inline";
                }
                else{
                    var tdraid = tdraid;
    
                    var scID = 'sc' + tdraid;
    
                    document.getElementById(scID).style.display = "inline";
                }
            }

            function revealTDRM() {
                document.getElementById('saveTDRM').style.display = "inline";
            }

            function save() {
                var vend = $('#vend').val();
                var po = $('#po').val();
                var dateCode = $('#dateCode').val();
                var program = $('#program').val();
                var itemID = $('#itemID').val();
                var scdNo = $('#scdNo').val();
                var file = $('#file').val();
                var mir = $('#mir').val();
                var storeID = $('#storeID').val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_savedata.php',

                    dataType: 'html',

                    data: {
                        vend: vend,
                        po: po,
                        dateCode: dateCode,
                        program: program,
                        itemID: itemID,
                        scdNo: scdNo,
                        file: file,
                        mir: mir,
                        storeID: storeID
                    },

                    success: function(html){
                        if (html == '1'){
                            alert('Data saved');
                        }
                    },
                });
            }

            function saveAll(ID) {
                tdrmid = ID;

                var detailSource = document.getElementsByName("tdraInput");
                var detailArray = new Array();

                Array.prototype.forEach.call(detailSource, function(el) {
                    var valueToPush = new Array();
                    valueToPush[0] = el.id;
                    valueToPush[1] = el.value;
                    detailArray.push(valueToPush);
                });

                $.ajax({
                    type: 'POST',
                    
                    url: 'fp/TDS_saveall.php',

                    dataType: 'html',

                    data: {
                        commentList: detailArray
                    },

                    success: function(html){
                        buildProcessingForm(tdrmid);
                    }
                });
            }

            function saveComments(tdraid){
                var tdraid = tdraid;

                var inputID = 'uc' + tdraid;

                comment = $( "#" + inputID ).val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_savecomments.php',

                    dataType: 'html',
                    
                    data: {
                        tdraid: tdraid,
                        comment: comment
                    },

                    success: function(html){
                        alert('Changes saved');
                    }
                });
            }

            function saveCommentsAdmin(tdraid){
                var tdraid = tdraid;

                var inputID = 'uc' + tdraid;
                var roleID = 'ur' + tdraid;
                var nameID = 'un' + tdraid;

                comment = $( "#" + inputID ).val();
                role = $( "#" + roleID ).val();
                name = $( "#" + nameID ).val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_savecommentsadmin.php',

                    dataType: 'html',
                    
                    data: {
                        tdraid: tdraid,
                        comment: comment,
                        role: role,
                        name: name
                    },

                    success: function(html){
                        alert('All changes saved');
                    }
                });
            }

            function saveTDRM(tdrmID){
                if($('#program').val() != ''){
                    var vend = $('#vend').val();
                    var po = $('#po').val();
                    var dateCode = $('#dateCode').val();
                    var program = $('#program').val();
                    var itemID = $('#itemID').val();
                    var scdNo = $('#scdNo').val();
                    var file = $('#file').val();
                    var mir = $('#mir').val();
                    var tdrmID = tdrmID;
    
                    $.ajax({
                        type: 'POST',
    
                        url: 'fp/TDS_saveTDRM.php',
    
                        dataType: 'html',
    
                        data: {
                            vend: vend,
                            po: po,
                            dateCode: dateCode,
                            program: program,
                            itemID: itemID,
                            scdNo: scdNo,
                            file: file,
                            mir: mir,
                            tdrmID: tdrmID
                        },
    
                        success: function(html){
                            if (html == '1'){
                                alert('Data saved');
                            }
                            else{
                                alert(html);
                            }
                            buildProcessingForm(tdrmID);
                        },
                    });
                }
                else{
                    alert('Please select a program');
                }
            }

            function sendEmail(tdrmID, name){
                var tdrmID = tdrmID;
                var name = name;

                $.ajax({
                    type: 'POST',

                    url: 'fp/TDS_Email.php',

                    dataType: 'html',

                    data: {
                        tdrmid: tdrmID,

                        name: name
                    },

                    success: function(html) {
                        alert('Reviewer alerted');
                    }
                });
            }

            function showUpBtn(){
                $('#upBtn').show();
            }

            function uploadDoc(rowID){
                $('#fileUpload').toggle();
            }

        </script>
    </head>
    <body $onload>
        $header
        <div id='container'>
            <div id='content'>
                <div class='holderDiv'>
                    <div id='mUpdate'>
                        <table class='table1'>
                            <tr>
                                <td>Add New TDRS</td>
                                <td></td>
                                <td><button onclick='newTDRM()' class='mainBtn'>Add</button></td>
                                <td class='explain'>Create a new test data review sheet.</td>
                            </tr>
                            <tr>
                                <td>Open Existing TDRS</td>
                                <td><input class='item' type='text' placeholder='Base #' id='enterID' class='baseBox'></td>
                                <td><button onclick='editPrev()' class='mainBtn'>Open</button></td>
                                <td class='explain'>Open previous Test Data Review Sheet.</td>
                            </tr>
                            <tr>
                                <td>Filter By Status</td>
                                <td>
                                    <select id='selectFilter' class='mainSelect' onchange='buildTable2()'>
                                        <option value="= 'Active'" selected>Active</option>
                                        <option value="= 'Completed'">Completed</option>
                                        <option value="LIKE '%'">All</option>
                                    </select>
                                </td>
                                <td></td>
                                <td class='explain'>View Test Data Review Sheets by status</td>
                            </tr>
                            <tr>
                                <td>Filter By Approver</td>
                                <td>$empList</td>
                                <td></td>
                                <td class='explain'>View all sheets associated with a specific employee</td>
                            </tr>
                        </table>
                        <div id='secondTable'>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

_FixedHTML;

?>