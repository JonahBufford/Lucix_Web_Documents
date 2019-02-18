<?php

// import common files

$title = "File Upload";
$pageTitle  = "File Upload";
$msg = "default message";

require_once 'connections.php';
require_once 'iconData.php';
require_once 'header.php';

//if(isset($_POST["submit"])) {

    $msg = '1';

    $rowID =  $_POST["rowID"];
    $pBase =  $_POST["pBase"];
    $pDash =  $_POST["pDash"];

    if ($pDash == ""){
        $folder = $pBase . "/";
    } else {
        $folder = $pBase . "-" . $pDash . "/";
    }
    
    $target_dir = "//CMDATASTORAGE/Workpad/Lucix_Applications/ItemID_Files/" . $folder;
    mkdir($target_dir);
    $textName = basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $textName;
    $uploadOk = 1;

    // Check if file already exists
    if (file_exists($target_file)) {
        $msg =  "This file is already in the directory. ";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 10000000) {
        $msg =  "Your file is too large (>10Mb). ";
        $uploadOk = 0;
    }
    
    if ($uploadOk == 0) {
        $msg =  "Your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

            //retrieve exiting purchasing data from dB
            $query = "SELECT ITM_PDOC
            FROM ITEM
            WHERE ITM_uid = '$rowID'";

            $result = odbc_exec ( $connENT, $query );

            odbc_fetch_row($result);

            $pDoc = odbc_result($result,"ITM_PDOC");

            $textName .= ", " . $pDoc;
            
            $sql = "UPDATE ITEM
            SET ITM_PDOC = '$textName'
            WHERE ITM_uid = $rowID";

            $result = odbc_exec ( $connENT, $sql );

            $msg = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. ";

        } else {
            $msg =  "There was an error uploading your file.";
        }
    }    
//}


echo "<br><br>";
echo <<<_FixedHTML2

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../../css/default.css">
    <link rel="stylesheet" type="text/css" href="../../css/itemID.css">
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
                    $msg
                    <br><br>
                    This page will close in 3 seconds.<br><br>Or click here to close it immediately: 
                    <input type='button' onclick='closeWindow()' value='Close' class='mainBtn'>
                </div>
            </div>
        </div>
    </div>
    <script>
        function closeWindow(){
            close();
        }

        setTimeout("window.close();",3000);

    </script>
</body>
</html>

_FixedHTML2;



?>