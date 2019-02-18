<?php

session_start ();

// define variables for common files
$title = "<a href='companyInfo.php'>Lucix Inc.</a>";

// import common files
require_once 'fp/iconData.php';
require_once 'fp/header.php';
require_once 'fp/connections.php';

$user = '';       

if(isset($_GET['id'])){
    $addID = $_GET['id'];
    $onload = "onLoad='approvalPage(\"$addID\")'";
}
else{
    $onload = "onLoad='buildTable2()'";
}

if(isset($_SESSION['username'])){
    $user = $_SESSION['username'];
}

$templateList = "
                    <option value=''>Select</option>";

$tempQuery = "SELECT *
                FROM APR_main
                WHERE APR_isTemplate = 1";

$tempResult = mysqli_query($conn,$tempQuery);

while($i = mysqli_fetch_assoc($tempResult)){
    $id = $i['APR_uid'];
    $tempName = $i['APR_tempName'];

    $templateList .= "<option value='$id'>$tempName</option>";
}

$templateList .= "</select>";

echo <<<_FixedHTML

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" type="text/css" href="../css/default.css">
        <link rel="stylesheet" type="text/css" href="../css/APR_approvalApplication.css">

        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

        <script src="../js/defaultJS.js"></script>
        <script src="../js/jquery-ui.min.js"></script>
        
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="/resources/demos/style.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script>
            
        </script>
        <script>
            function makeClickable() {
                $(".ui-state-highlight").mousedown( function() {
                    var id = $(this).attr('id');
                    var name = $(this).attr('name');
                    $('#selectedLIid').val(id);
                    displayInfoForApprover(id, name);
                });
            }

            function makeSortable() {
                $( "#sortable1, #sortable2, #sortable3" ).sortable({
                    connectWith: ".connectedSortable"
                });
                $(".ui-state-highlight").mousedown( function() {
                    var id = $(this).attr('id');
                    $('#selectedLIid').val(id);
                    var empName = $("#in" + id).val();
                    var liName = $("#" + id).attr('name');
                    displayInfo(empName,id,liName);
                });
                $("#sortable1, #sortable2, #sortable3").on( "sortreceive", function() {
                    var name = $(this).attr('id');
                    var id = $('#selectedLIid').val();
                    if($('#' + id).attr('name') == 'sortable2' ){
                        replace();
                    }
                    $('#' + id).attr('name',name);
                    var empName = $("#in" + id).val();
                    displayInfo(empName, id, name);
                });
            }

            function addAssignedDetail(id, name) {
                var id = id;

                var counter = $("#co" + id).val();

                var detailId = counter + "aD" + id;

                var input = "<input onchange='updateAssignedDetail(\"" + id + "\",\"" + counter + "\")' id='detail" + counter + "'>";
                var assignedDetail = "<input type='hidden' id='" + detailId + "' value='' name='assignedDetail'>";

                $("#" + id).append(assignedDetail);
                $("#assignedDetail").append(input);

                counter++;

                $("#co" + id).val(counter);
            }

            function addDataField() {
                var counter = $('#counter').val();
                
                var field = "<div id=\'dataField" + counter + "\'>" + 
                                "Data Field Name <input type=\'text\' name='dataFieldInput'> " + 
                                "<button onclick='deleteDataField(" + counter + ")'>Delete</button>" +
                            "</div>";
                            
                $("#dataField").append(field);
                
                counter++;
                
                $('#counter').val(counter);
            }

            function addDataFieldToApproval() {
                var counter = $('#counter').val();
                
                var field = "<div id='dataField" + counter + "'>" + 
                                "Data Field Name " + 
                                "<input type='text' name='dataFieldInput' id='dfname" + counter + "'> Data Field Value " + 
                                "<input type='text' name='dataInput' id='dfval" + counter + "'> " + 
                                "<button id='del" + counter + "' onclick='deleteDataField(" + counter + ")'>Delete</button> " +
                            "</div>";
                            
                $("#dataField").append(field);
                
                counter++;
                
                $('#counter').val(counter);
            }

            function addFileSlot() {
                var counter = $('#fileCounter').val();
                
                counter++;

                var field = "<input type='file' name='fileToUpload" + counter + "' id='fileToUpload" + counter + 
                            "' class='choseBtn' onclick='showUpBtn()'>" +
                            " Title <input type='text' name='title" + counter + "'> <br>";

                $('#fileInputs').append(field);

                $('#fileCounter').val(counter);
            }

            function approvalPage(id) {
                var id = id;
                var user = '$user';

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_approvalSheetPage.php',

                    dataType: 'html',

                    data: {
                        id: id,
                        user: user
                    },

                    success: function(html) {
                        document.getElementById('mUpdate').innerHTML = html;
                        showApprovers(id);
                    }
                });
            }

            function approve(id, sheetId) {
                var id = id;
                var sheetId = sheetId;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_approve.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        approvalPage(sheetId);
                        if(html > 0){
                            emailAvailable(sheetId,html);
                        }
                    }
                });
            }

            function approveSheet(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_approveSheet.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        if(html == 1){
                            alert('Approved');
                        }
                        else{
                            alert(html);
                        }
                        email(id, "notifEnd", "Approved");
                        approvalPage(id);
                    }
                });
            }

            function back() {
                window.location.replace('http://localhost/Approval_Application/php/APR_approvalApplication.php');
            }

            function buildApprovalEdit() {
                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_editApprovals.php',

                    dataType: 'html',

                    success: function(html) {
                        document.getElementById('approvalEdit').innerHTML = html;
                        makeSortable();
                    }
                });
            }

            function buildApprovalEditFromApprovalSheet(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_editApprovals.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        document.getElementById('approvalEdit').innerHTML = html;
                        makeSortable();
                        $('.viewApprovalButton').toggle();
                        $('#editApprovalButton').toggle();
                    }
                });
            }

            function buildApprovalEditFromTable(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_editApprovals.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        document.getElementById('approvalEdit').innerHTML = html;
                        makeSortable();
                    }
                });
            }

            function buildFileAdd() {
                var user = '$user';

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_buildFileAdd.php',

                    dataType: 'html',

                    data: {
                        user: user
                    },

                    success: function(html) {
                        document.getElementById('addFiles').innerHTML = html;
                    }
                });
            }

            function buildFileAdd(id) {
                var id = id;
                var user = '$user';

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_buildFileAdd.php',

                    dataType: 'html',

                    data: {
                        id: id,
                        user: user
                    },

                    success: function(html) {
                        document.getElementById('addFiles').innerHTML = html;
                    }
                });
            }

            function buildProcessingForm(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_processingForm.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },
                    
                    success: function(html) {
                        document.getElementById('mUpdate').innerHTML = html;
                        makeSortable();
                    }
                });
            }

            function buildTable2() {
                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_buildTable2.php',

                    dataType: 'html',

                    success: function(html) {
                        document.getElementById('secondTable').innerHTML = html;
                    }
                });
            }

            function buildTable2Filtered() {
                var template = $('#tempName').val();
                var status = $('#status').val();
                var creator = $('#creator').val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_buildTable2.php',

                    dataType: 'html',

                    data: {
                        template: template,
                        status: status,
                        creator: creator
                    },

                    success: function(html) {
                        document.getElementById('secondTable').innerHTML = html;
                    }
                });
            }

            function cancel(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_cancel.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        if(html == 1){
                            alert('cancelled');
                        }
                        else{
                            alert(html);
                        }
                        email(id, "notifEnd", "Canceled");
                        approvalPage(id);
                    }
                });
            }

            function changeOrder(id) {
                var id = id;
                var val = $("#selectOrder").val();
                $("#" + id).val(val);
            }

            function changeStatus(id) {
                var id = id;
                var val = $("#" + id).val();
                if(val == 0){
                    $("#" + id).val(1);
                }
                else{
                    $("#" + id).val(0);
                }
                if(id == "ordered"){
                    $(".orderInfo").toggle();
                }
            }

            function checkIdForTemplate(id) {

                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_checkIdForTemplate.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        if(html == 1){
                            getTemplateName(id);
                        }
                        else{
                            approvalPage(id);
                        }
                    }
                });
            }

            function deleteDataField(num) {
                var num = num;
                $('#dataField' + num).remove();
            }

            function deleteEditData(id) {
                var id = id;
                document.getElementById("editData" + id).style.color = "red";
                $("#dfname" + id).attr("name","dataFieldDelete").prop('disabled', true);
                $("#del" + id).toggle();
                $("#un" + id).toggle();
            }

            function deptSelected() {
                var dept = $('#deptSelect').val();
                var counter = $('#empCounter').val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_filterEmployeeList.php',

                    dataType: 'html',

                    data: {
                        dept: dept,
                        counter: counter
                    },

                    success: function(html) {
                        document.getElementById('empListCell').innerHTML = html;
                        makeSortable();
                    }
                });
            }

            function detailArray(name) {
                var detailSource = document.getElementsByName(name);
                var detailArray = new Array();
                
                Array.prototype.forEach.call(detailSource, function(el) {
                    var valueToPush = new Array();
                    valueToPush[0] = el.id;
                    valueToPush[1] = el.value;
                    if(valueToPush[1] != ''){
                        detailArray.push(valueToPush);
                    }
                });

                return detailArray;
            }

            function displayInfo(empName, id, liName) {
                var empName = empName;
                var id = id;
                var liName = liName;
                var not1 = $("#n1" + id).val();
                var notA = $("#nA" + id).val();
                var notC = $("#nC" + id).val();
                var order = $("#or" + id).val();
                var length = $("#" + liName + " ul li").length;
                var isOrdered = $("#ordered").val();
                var counter = $("#co" + id).val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_displayApprovalInfo.php',

                    dataType: 'html',

                    data: {
                        empName: empName,
                        id: id,
                        liName: liName,
                        not1: not1,
                        notA: notA,
                        notC: notC,
                        order: order,
                        length: length,
                        isOrdered: isOrdered,
                        counter: counter
                    },

                    success: function(html) {
                        document.getElementById('approvalData').innerHTML = html;
                        if(isOrdered != "0"){
                            $(".orderInfo").toggle();
                        }
                        setAssignedDetails(id, counter);
                    }
                });
            }

            function editApprovalSheet(id, template) {
                var id = id;
                var template = template;
                var edit = 1;
                var sheet = 1;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_templatePage.php',

                    dataType: 'html',

                    data: {
                        id: id,
                        template: template,
                        edit: edit,
                        sheet: sheet
                    },

                    success: function(html) {
                        document.getElementById('mUpdate').innerHTML = html;
                        buildApprovalEditFromTable(id);
                        buildFileAdd(id);
                    }
                });
            }

            function editTemplate(template, id) {
                var id = id;
                var template = template;
                var edit = 1;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_templatePage.php',

                    dataType: 'html',

                    data: {
                        id: id,
                        template: template,
                        edit: edit
                    },

                    success: function(html) {
                        document.getElementById('mUpdate').innerHTML = html;
                        buildApprovalEditFromTable(id);
                        buildFileAdd(id);
                    }
                });
            }

            function email(id,notif,status) {
                var id = id;
                var notif = notif;
                var status = status;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_Email.php',

                    dataType: 'html',

                    data: {
                        id: id,
                        notif: notif,
                        status: status
                    },

                    success: function(html) {
                        alert('Email sent');
                    }
                });
            }

            function emailAvailable(id, order) {
                var id = id;
                var notif = "notifAvailable";
                var status = "Pending";
                var order = order;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_Email.php',

                    dataType: 'html',

                    data: {
                        id: id,
                        notif: notif,
                        status: status,
                        order: order
                    },

                    success: function(html) {
                    }
                });
            }

            function getTemplateListValue() {
                var id = $('#templateList').val();
                getTemplateName(id);
            }

            function getTemplateName(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_getTemplateName.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        editTemplate(html, id);
                    }
                });
            }

            function liArray(ul) {
                var ul = ul;

                var liArray = new Array();

                $(ul + " li").each( function() {
                    var id = $(this).attr("id");

                    var nameid = "#in" + id;
                    var not1id = "#n1" + id;
                    var notAid = "#nA" + id;
                    var notCid = "#nC" + id;
                    var orderid = "#or" + id;
                    var dateid = "#da" + id;

                    var li = new Array();

                    li[0] = $(nameid).val();
                    li[1] = $(not1id).val();
                    li[2] = $(notCid).val();
                    li[3] = $(orderid).val();
                    li[4] = id;
                    li[5] = $(dateid).val();
                    li[6] = $(notAid).val();

                    liArray.push(li);
                });

                return liArray;
            }

            function newApprovalFromTemplate() {
                var user = '$user';

                if( user != '' ){
                    var id = $('#templateListCreate').val();
                    var template = $("#templateListCreate option:selected").text();
                    var newFromTemplate = 1;

                    if(id != ""){
                        $.ajax({
                            type: 'POST',
        
                            url: 'fp/APR_templatePage.php',
        
                            dataType: 'html',
        
                            data: {
                                id: id,
                                template: template,
                                newFromTemplate: newFromTemplate
                            },
        
                            success: function(html) {
                                document.getElementById('mUpdate').innerHTML = html;
                                buildApprovalEditFromTable(id);
                                buildFileAdd();
                            }
                        });
                    }
                }

                else{
                    alert('You must be logged in to access this function');
                }
            }

            function newApprovalFromScratch() {
                var user = '$user';
                var template = '';

                if(user != "") {
                    $.ajax({
                        type: 'POST',
    
                        url: 'fp/APR_templatePage.php',
    
                        dataType: 'html',
    
                        data: {
                            creator: user,
                            template: template
                        },
    
                        success: function(html){
                            document.getElementById('mUpdate').innerHTML = html;
                            buildApprovalEdit();
                            buildFileAdd();
                        }
                    });
                }

                else{
                    alert('You must be logged in to access this function');
                }
            }

            function newTemplate() {
                var user = '$user';

                if(user != ""){
                    $.ajax({
                        type: 'POST',
    
                        url: 'fp/APR_templatePage.php',
    
                        dataType: 'html',
    
                        data: {
                            creator: user
                        },
    
                        success: function(html){
                            document.getElementById('mUpdate').innerHTML = html;
                            buildApprovalEdit();
                        }
                    });
                }

                else{
                    alert('You must be logged in to access this function');
                }
            }

            function openExistingApproval() {
                var id = $("#enterID").val();
                checkIdForTemplate(id);
            }

            function replace() {
                var item = $("#selectedLIid").val();

                var name = $("#in" + item).val();
                var counter = $('#empCounter').val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_replaceLI.php',

                    dataType: 'html',

                    data: {
                        item: item,
                        name: name,
                        counter: counter
                    },

                    success: function(html) {
                        $('#sortable2').prepend(html);
                        counter++;
                        $('#empCounter').val(counter);
                        makeSortable();
                    }
                });
            }

            function resetApprovalSheet(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_resetApprovalSheet.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        if(html == 1){
                            alert('Reset');
                        }
                        else{
                            alert(html);
                        }
                        approvalPage(id);
                    }
                });
            }

            function saveDetailData(id, textId) {
                var id = id;
                var val = $("#" + textId).val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_saveDetailData.php',

                    dataType: 'html',

                    data: {
                        id: id,
                        val: val
                    },

                    success: function(html) {
                        if(html == 1){
                            alert('saved');
                        }
                        else{
                            alert(html);
                        }
                    }
                });
            }

            function saveEdit(id, isTemplate) {
                var id = id;
                var isTemplate = isTemplate;
                var user = '$user';

                var detailSource = document.getElementsByName("dataFieldInput");
                var detailArray = new Array();
                
                Array.prototype.forEach.call(detailSource, function(el) {
                    var valueToPush = new Array();
                    valueToPush[0] = el.id;
                    valueToPush[1] = el.value;
                    if(valueToPush[1] != ''){
                        detailArray.push(valueToPush);
                    }
                });

                var detailSource = document.getElementsByName("dataFieldInputEdit");
                var detailEditArray = new Array();
                
                Array.prototype.forEach.call(detailSource, function(el) {
                    var valueToPush = new Array();
                    valueToPush[0] = el.id;
                    valueToPush[1] = el.value;
                    if(valueToPush[1] != ''){
                        detailEditArray.push(valueToPush);
                    }
                });

                var detailSource = document.getElementsByName("dataFieldDelete");
                var detailDeleteArray = new Array();
                
                Array.prototype.forEach.call(detailSource, function(el) {
                    var valueToPush = new Array();
                    valueToPush[0] = el.id;
                    valueToPush[1] = el.value;
                    if(valueToPush[1] != ''){
                        detailDeleteArray.push(valueToPush);
                    }
                });

                var assignedDetailArray = new Array();

                $("#sortable1 li").each( function() {
                    var id = $(this).attr("id");

                    var counterid = "#co" + id;
                    var nameid = "#in" + id;
                    var counter = $(counterid).val();
                    
                    var name = $(nameid).val();

                    for(x = 1; x < counter; x++) {
                        var detail = new Array();
                        
                        var fullId = x + "aD" + id;
                        var category = $("#" + fullId).val();
                        
                        detail[0] = fullId;
                        detail[1] = category;
                        detail[2] = name;

                        if(category != ''){
                            assignedDetailArray.push(detail);
                        }
                    }
                });

                var temp = $('#templateName').val();
                var name = $('#name').val();
                var notes = $('#notes').val();

                var assignments = liArray("#sortable1");
                var notifications = liArray("#sortable3");
                var isOrdered = $("#ordered").val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_saveEditTemplate.php',

                    dataType: 'html',

                    data: {
                        id: id,
                        user: user,
                        temp: temp,
                        name: name,
                        notes: notes,
                        detailArray: detailArray,
                        detailEditArray: detailEditArray,
                        detailDeleteArray: detailDeleteArray,
                        assignments: assignments,
                        notifications: notifications,
                        isOrdered: isOrdered,
                        assignedDetailArray: assignedDetailArray,
                        isTemplate: isTemplate
                    },

                    success: function(html) {
                        if(html == '1'){
                            alert('saved');
                        }
                        else{
                            alert(html);
                        }
                    }
                });
            }

            function saveMainNotes(id) {
                var id = id;
                var notes = $('#mainNotes').val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_saveMainNotes.php',

                    dataType: 'html',

                    data: {
                        notes: notes,
                        id: id
                    },

                    success: function(html) {
                        if(html == 1){
                            alert('Saved');
                        }
                        else{
                            alert(html);
                        }
                    }
                });
            }

            function saveNewApproval() {
                var user = '$user';

                var detailSource = document.getElementsByName("dataFieldInput");
                var fullDetailArray = new Array();
                
                Array.prototype.forEach.call(detailSource, function(el) {
                    var valueToPush = new Array();
                    valueToPush[0] = el.id;
                    valueToPush[1] = el.value;
                    var fullId = valueToPush[0];
                    var idNum = fullId.substring(6);
                    var inputId = "dfval" + idNum;
                    valueToPush[2] = $("#" + inputId).val();
                    if(valueToPush[1] != ''){
                        fullDetailArray.push(valueToPush);
                    }
                });

                var assignedDetailArray = new Array();

                $("#sortable1 li").each( function() {
                    var id = $(this).attr("id");

                    var counterid = "#co" + id;
                    var nameid = "#in" + id;
                    var counter = $(counterid).val();
                    
                    var name = $(nameid).val();

                    for(x = 1; x < counter; x++) {
                        var detail = new Array();
                        
                        var fullId = x + "aD" + id;
                        var val = $("#" + fullId).val();
                        
                        detail[0] = fullId;
                        detail[1] = val;
                        detail[2] = name;

                        if( detail[1] != ''){
                            assignedDetailArray.push(detail);
                        }
                    }
                });
                
                var temp = $('#templateName').val();
                var name = $('#name').val();
                var notes = $('#notes').val();

                var assignments = liArray("#sortable1");
                var notifications = liArray("#sortable3");
                var isOrdered = $("#ordered").val();

                if(name != "" || temp != ""){
                    $.ajax({
                        type: 'POST',
    
                        url: 'fp/APR_saveEditTemplate.php',
    
                        dataType: 'html',
    
                        data: {
                            user: user,
                            temp: temp,
                            name: name,
                            notes: notes,
                            fullDetailArray: fullDetailArray,
                            assignments: assignments,
                            notifications: notifications,
                            isOrdered: isOrdered,
                            assignedDetailArray: assignedDetailArray
                        },
    
                        success: function(html) {
                            $('#uid').val(html);
                            $('#form').submit();
                            email(html, "notifBeginning", "Pending");
                        }
                    });
                }

                else{
                    alert('Please enter name');
                }
            }

            function saveNewTemplate() {
                var user = '$user';
                
                var detailSource = document.getElementsByName("dataFieldInput");
                var detailArray = new Array();
                
                Array.prototype.forEach.call(detailSource, function(el) {
                    var valueToPush = new Array();
                    valueToPush[0] = el.id;
                    valueToPush[1] = el.value;
                    if(valueToPush[1] != ''){
                        detailArray.push(valueToPush);
                    }
                });
                
                var temp = $('#templateName').val();
                var name = $('#name').val();
                var notes = $('#notes').val();

                var assignments = liArray("#sortable1");
                var notifications = liArray("#sortable3");
                var isOrdered = $("#ordered").val();

                var assignedDetailArray = new Array();

                $("#sortable1 li").each( function() {
                    var id = $(this).attr("id");

                    var counterid = "#co" + id;
                    var nameid = "#in" + id;
                    var counter = $(counterid).val();
                    
                    var name = $(nameid).val();

                    for(x = 1; x < counter; x++) {
                        var detail = new Array();
                        
                        var fullId = x + "aD" + id;
                        var val = $(fullId).val();
                        
                        detail[0] = fullId;
                        detail[1] = val;
                        detail[2] = name;

                        assignedDetailArray.push(detail);
                    }
                });

                if(name != ""){
                    $.ajax({
                        type: 'POST',
    
                        url: 'fp/APR_saveNewTemplate.php',
    
                        dataType: 'html',
    
                        data: {
                            user: user,
                            temp: temp,
                            name: name,
                            notes: notes,
                            detailArray: detailArray,
                            assignments: assignments,
                            notifications: notifications,
                            isOrdered: isOrdered,
                            assignedDetailArray: assignedDetailArray
                        },
    
                        success: function(html) {
                            back();
                        }
                    });
                }

                else{
                    alert('Please enter a name');
                }
            }

            function searchById() {
                var id = $('#enterID').val();
                buildProcessingForm(id);
            }

            function setAssignedDetails(id, counter) {
                var id = id;
                var counter = counter;

                for(x = 1; x < counter; x++){
                    detail = $("#" + x + "aD" + id).val();
                    $("#detail" + x).val(detail);
                }
            }

            function setStatus() {
                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_setStatus.php',

                    dataType: 'html',

                    success: function(html) {
                        if(html == 1){
                            alert('set');
                        }

                        else{
                            alert(html);
                        }
                    }
                });
            }

            function showApprovers(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_showApprovers.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) { 
                        document.getElementById('approvalEdit').innerHTML = html;
                        makeClickable();
                    }
                });
            }

            function showUpBtn() {
                $('#upBtn').show();
            }

            function viewApprovals(id) {
                var id = id;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_showApprovers.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) { 
                        document.getElementById('approvalEdit').innerHTML = html;
                        makeClickable();
                        $('.viewApprovalButton').toggle();
                        $('#editApprovalButton').toggle();
                    }
                });
            }

            function undoDelete(id) {
                var id = id;
                document.getElementById("editData" + id).style.color = "black";
                $("#dfname" + id).attr("name","dataFieldInputEdit").prop('disabled', false);
                $("#del" + id).toggle();
                $("#un" + id).toggle();
            }

            function undoApprove(id, sheetId) {
                var id = id;
                var sheetId = sheetId;

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_undoApproval.php',

                    dataType: 'html',

                    data: {
                        id: id
                    },

                    success: function(html) {
                        approvalPage(sheetId);
                    }
                });
            }

            function updateAssignedDetail(id,counter) {
                var id = id;
                var counter = counter;
                var detail = $("#detail" + counter).val();
                $("#" + counter + "aD" + id).val(detail);
            }

            function updateNotes(id, textid) {
                var id = id;
                var textid = textid;

                var notes = $("#" + textid).val();

                $.ajax({
                    type: 'POST',

                    url: 'fp/APR_updateNotes.php',

                    dataType: 'html',

                    data: {
                        id: id,
                        notes: notes
                    },

                    success: function(html) {
                        if(html == 1) {
                            alert('Saved');
                        }
                        else{
                            alert(html);
                        }
                    }
                });
            }
        </script>

        <title>Lucix Corporation</title>

    </head>
    <body $onload>
        $header
            <div id='container'>
                <div id='content'>
                    <div class='holderDiv'>
                        <div id='mUpdate'>
                            <table class='table1'>
                                <tr>
                                    <td>Add New Approval From Template</td>
                                    <td><select id='templateListCreate' class='mainSelect'>$templateList</td>
                                    <td><button onclick='newApprovalFromTemplate()' class='mainBtn'>Add</button></td>
                                    <td class='explain'>Create a new approval sheet from a premade template.</td>
                                </tr>
                                <tr>
                                    <td>Create New Approval From Scratch</td>
                                    <td></td>
                                    <td><button onclick='newApprovalFromScratch()' class='mainBtn'>Add</button></td>
                                    <td class='explain'>Create a new approval sheet from scratch</td>
                                </tr>
                                <tr>
                                    <td>Open Existing Approval</td>
                                    <td><input class='item' type='text' placeholder='Base #' id='enterID' class='baseBox'></td>
                                    <td><button onclick='openExistingApproval()' class='mainBtn'>Open</button></td>
                                    <td class='explain'>Open previous approval sheet.</td>
                                </tr>
                                <tr>
                                    <td>Create New Template</td>
                                    <td></td>
                                    <td><button onclick='newTemplate()' class='mainBtn'>Go</td>
                                    <td class='explain'>Create a new template for approvals.</td>
                                </tr>
                                <tr>
                                    <td>Edit Template</td>
                                    <td><select id='templateList' class='mainSelect'>$templateList</td>
                                    <td><button onclick='getTemplateListValue()' class='mainBtn'>Go</td>
                                    <td class='explain'>Edit an existing template</td>
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