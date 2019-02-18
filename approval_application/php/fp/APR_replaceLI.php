<?php

$name = $_POST['name'];
$counter = $_POST['counter'];

$LIid = "li" . $counter;
$inID = "in" . $LIid;
$not1id = "n1" . $LIid;
$notAid = "nA" . $LIid;
$notCid = "nC" . $LIid;
$orderid = "or" . $LIid;
$counterid = "co" .$LIid;
$dateId = "da" . $LIid;

$output = "<li class='ui-state-highlight' id='$LIid' name='sortable2'>
                $name
                <input value='$name' type='hidden' id='$inID'>
                <input value='0' type='hidden' id='$not1id'>
                <input value='0' type='hidden' id='$notAid'>
                <input value='0' type='hidden' id='$notCid'>
                <input value='0' type='hidden' id='$orderid'>
                <input value='1' type='hidden' id='$counterid'>
                <input value='' type='hidden' id='$dateId'>
            </li>";

echo $output;

?>