<?php

$addNew = 
"<html>
<head>
    <script type='text/javascript' src='../js/defaultJQuery.js'></script>
    <script>
        function addReviewerButton(tdrmID){
            alert('adding reviewer to database');
            var name = $('#reviewerName').val();
            var role = $('#reviewerRole').val();
            newTDRA(tdrmID, name, role );
        }
        function createNewApprovalSheetTDRA(tdrmID){
            alert('Adding reviewer');
            var baseNum = tdrmID;
            $.ajax({
                type: 'POST',
                url: 'fp/addReviewerForm.php',
                dataType: 'html',
                data: {
                    TDRMID: baseNum
                },
                success: function(html){
                    document.getElementById('updateTDRA').innerHTML = html;
                },

            });
        }
        function newTDRA(tdrmID, nameInput, roleInput){
            var baseNum = tdrmID;
            var name = nameInput;
            var role = roleInput;
            $.ajax({
                type: 'POST',
                url: 'fp/newTDRA.php',
                dataType: 'html',
                data: {
                    TDRMID: baseNum,
                    name: name,
                    role: role
                },
                success: function(html){
                    alert(html);
                    configureTDRM(baseNum);
                    configureTDRA(baseNum);
                },

            });
        }
        function newTDRM() {
            $.ajax({
                type: 'POST',
                url: 'fp/tdnew.php',
                dataType: 'html',
                success: function(html){
                    newTDRA(html,'0','Component Engineer');
                },

            });
        }function configureTDRA(confTerm){
            var baseNum = confTerm;
            $.ajax({
                type: 'POST',
                url: 'fp/confTDRA.php',
                dataType: 'html',
                data: {
                    TDRMID: baseNum
                },
                success: function(html){
                    document.getElementById('updateTDRA').innerHTML = html;
                },

            });
        }
        function configureTDRM(confTerm){
            var baseNum = confTerm;
            $.ajax({
                type: 'POST',
                url: 'fp/confTDRM.php',
                dataType: 'html',
                data: {
                    TDRMID: baseNum
                },
                success: function(html){
                    document.getElementById('update').innerHTML = html;
                },

            });
        }
        function editPrev(){
            var idToEdit = $('#enterID').val();
            configureTDRM(idToEdit);
            configureTDRA(idToEdit);
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
                url: 'fp/savedata.php',
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
                        configureTDRM(storeID);
                    }
                },
            });
        }
        function viewPrev(){
            var baseNum = $('#enterID').val();
            $.ajax({
                type: 'POST',
                url: 'fp/viewTDRM.php',
                dataType: 'html',
                data: {
                    TDRMID: baseNum
                },
                success: function(html){
                    document.getElementById('update').innerHTML = html;
                },

            });
        }
    </script>
</head>
<body>
    <div>
        <p>Test Data Sheets</p>
    </div>
    <div id='update'>
        <button onclick='newTDRM()'>Add New</button>
    </div>
    <div>
        <input type='text' id='enterID'>
        <button onclick='editPrev()'>Edit Previous Form</button>
        <button onclick='viewPrev()'>View Previous Form</button>
    </div>
    <div id='updateTDRA'></div>
    <div id='approvalForm'></div>
</body>
</html>";
echo $addNew;

?>