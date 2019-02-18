<?php

require_once 'connections.php';

$counter = 1;

$newDF = "addDataField()";
$notes = "";
$back = "back()";
$addFiles = "";
$class = "";

if(isset($_POST['template'])){
    $template = $_POST['template'];
    if($template == ''){
        $nameInput = "<div class='titleRow'>Name <input type='text' id='name' value=''></div>";
        $dataField = "<div id='dataField1'>
                        Data Field Name 
                        <input type='text' name='dataFieldInput' id='dfname1'> 
                        Data Field Value 
                        <input type='text' name='dataInput' id='dfval1'>
                        <button onclick='deleteDataField(1)'>Delete</button>
                    </div>";
        $counter++;
        $save = "saveNewApproval()";
        $checkIfTemplate = "";
        $title = "New Approval From Scratch";
        
        $newDF = "addDataFieldToApproval()";

        $addFiles = "<div id='addFiles'></div>";
        $templateRow = "<input type='hidden' value='None' id='templateName'>";
    }
    else{
        $nameInput = "<input type='hidden' id='name' value=''>";
        $tempId = $_POST['id'];

        $mainQuery = "SELECT *
                      FROM APR_main
                      WHERE APR_uid = '$tempId'";

        $mainResult = mysqli_query($conn,$mainQuery);
        
        $info = mysqli_fetch_assoc($mainResult);
        
        $notes = $info['APR_notes'];

        $detailQuery = "SELECT *
                        FROM APR_details
                        WHERE APR_details_sheetID = '$tempId' AND APR_details_assignedToOther = 0";

        $detailResult = mysqli_query($conn,$detailQuery);

        $dataField = "";
        
        if(isset($_POST['edit'])){
            if(isset($_POST['sheet'])){
                $name = $info['APR_name'];
                $title = "Edit " . $template . " " . $tempId;
                $nameInput = "<div class='titleRow'>Name <input type='text' id='name' value='$name'></div>";
                $checkIfTemplate = "disabled";

                $save = "saveEdit($tempId, 0)";
                $back = "approvalPage($tempId)";

                $addFiles = "<div id='addFiles'></div>";
            }
            else{
                $title = "Edit Template";
                $checkIfTemplate = "";

                $save = "saveEdit($tempId, 1)";
                $class = "style='width:96%'";
            }
            while($i = mysqli_fetch_assoc($detailResult)){
                $fieldId = $i['APR_details_uid'];
                $fieldName = $i['APR_details_category'];
    
                $dataField .= "<div id='editData$fieldId'>
                                    Data Field Name
                                    <input type='text' id='dfname$fieldId' name='dataFieldInputEdit' value='$fieldName'>
                                    <button id='del$fieldId' onclick='deleteEditData($fieldId)'>Delete</button>
                                    <button id='un$fieldId' onclick='undoDelete($fieldId)' class='hidden'>Undo Delete</button>
                                </div>";
            }
        }

        else if(isset($_POST['newFromTemplate'])){
            $title = "Create New Approval From Template";
            $checkIfTemplate = "disabled";
            $nameInput = "<div class='titleRow'>Name <input type='text' id='name' value=''></div>";
            while($i = mysqli_fetch_assoc($detailResult)){
                $fieldName = $i['APR_details_category'];
    
                $dataField .= "<div id='dataField$counter'>
                                    Data Field Name
                                    <input type='text' name='dataFieldInput' value='$fieldName' id='dfname$counter'> Data Field Value 
                                    <input type='text' name='dataInput' id='dfval$counter'>
                                    <button id='del$counter' onclick='deleteDataField($counter)'>Delete</button>
                                </div>";
                
                $counter++;
            }

            $save = "saveNewApproval()";
            $newDF = "addDataFieldToApproval()";

            $addFiles = "<div id='addFiles'></div>";
        }
        $templateRow = "<div class='titleRow'>Template <input type='text' id='templateName' value='$template' $checkIfTemplate></div>";
    }
}
else{
    $template = "";
    $nameInput = '';
    $dataField = "<div id='dataField1'>
                    Data Field Name 
                    <input type='text' name='dataFieldInput'> 
                    <button onclick='deleteDataField(1)'>Delete</button>
                </div>";
    $counter++;
    $save = "saveNewTemplate()";
    $checkIfTemplate = "";
    $title = "Create New Template";
    $class = "style='width:96%'";
    $templateRow = "<div class='titleRow'>Template <input type='text' id='templateName' value='$template' $checkIfTemplate></div>";
}

$output =  "<div class='titleRow'>$title</div>
            <button onclick='$back'>Back</button>
            <button onclick='$save'>Save</button>
            $templateRow
            $nameInput
            Description/Notes<br><textarea id='notes' rows='2' cols='30'>$notes</textarea><br>
            <div class='container'>
                <div id='dataField' $class><button onclick='$newDF' class='bigBtn'>Add Data Field</button><br><br>$dataField</div>
                $addFiles
            </div>
            <input type='hidden' id='counter' value='$counter'>
            <div id='approvalEdit' class='container'></div>";

echo $output;

?>