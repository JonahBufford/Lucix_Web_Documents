<?php

$name = $_POST['empName'];
$id = $_POST['id'];
$liName = $_POST['liName'];
$not1 = $_POST['not1'];
$notA = $_POST['notA'];
$notC = $_POST['notC'];
$order = $_POST['order'];
$length = $_POST['length'];
$isOrdered = $_POST['isOrdered'];
$counter = $_POST['counter'];

$not1Id = "n1" . $id;
$notAId = "nA" . $id;
$notCId = "nC" . $id;
$orderId = "or" . $id;

if($not1 == 0){
    $not1Checked = "";
}
else{
    $not1Checked = "checked";
}

if($notA == 0){
    $notAChecked = "";
}
else{
    $notAChecked = "checked";
}

if($notC == 0){
    $notCChecked = "";
}
else{
    $notCChecked = "checked";
}

if($isOrdered== 0){
    $isOrderedChecked = "";
}
else{
    $isOrderedChecked = "checked";
}

$selectOrder = "<select id='selectOrder' onchange='changeOrder(\"$orderId\")'>
                    <option value='0'>Select</option>";

for($x = 1; $x <= 20; $x++){
    if($x == $order){
        $selectOrder .= "<option value='$x' selected>$x</option>";
    }
    else{
        $selectOrder .= "<option value='$x'>$x</option>";
    }
}

$details = "";

for($x = 1; $x < $counter; $x++){
    $details .= "<input id='detail" . $x . "' onchange='updateAssignedDetail(\"$id\",\"$x\")'>";
}

$selectOrder .= "</select>";

if($liName == "sortable1"){
    $output = "<tr>
                    <td>Approval Data</td>
                </tr>
                <tr>
                    <td>$name</td>
                </tr>
                <tr>
                    <td>
                        <input type='checkbox' id='note1st' onclick='changeStatus(\"$not1Id\")' $not1Checked>
                        <label for='note1st'>Notify Upon Creation</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='checkbox' id='noteAble' onclick='changeStatus(\"$notAId\")' $notAChecked>
                        <label for='note1st'>Notify when able to approve</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='checkbox' id='notelast' onclick='changeStatus(\"$notCId\")' $notCChecked>
                        <label for='notelast'>Notify Upon Completion</label>
                    </td>
                </tr>
                <tr>
                    <td><div id='assignedDetail'>$details</div></td>
                </tr>
                <tr>
                    <td><button onclick='addAssignedDetail(\"$id\",\"$name\")'>Add Approval</button></td>
                </tr>
                <tr>
                    <td>
                        <input type='checkbox' id='orderedCheckbox' onclick='changeStatus(\"ordered\")' $isOrderedChecked>
                        <label for='ordered'>Ordered</label>
                    </td>
                </tr>
                <tr class='orderInfo'>
                    <td>Order Number $selectOrder</td>
                </tr>
                <tr class='orderInfo'>
                    <td class='explain'>Lower numbers in the order must be completed before higher ones</td>
                </tr>";
}

else if($liName == "sortable2"){
    $output = "<tr>
                    <td>Approval Data</td>
                </tr>
                <tr>
                    <td>$name</td>
                </tr>";
}

else{
    $output = "<tr>
                    <td>Approval Data</td>
                </tr>
                <tr>
                    <td>$name</td>
                </tr>
                <tr>
                    <td>
                        <input type='checkbox' id='note1st' onclick='changeStatus(\"$not1Id\")' $not1Checked>
                        <label for='note1st'>Notify Upon Creation</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type='checkbox' id='notelast' onclick='changeStatus(\"$notCId\")' $notCChecked>
                        <label for='notelast'>Notify Upon Completion</label>
                    </td>
                </tr>";
}

echo $output;

?>