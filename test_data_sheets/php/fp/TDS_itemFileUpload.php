<?php

// import common files

$title = "File Upload";
$pageTitle  = "File Upload";
$msg = "default message";

require_once 'connections.php';
require_once 'TDS_iconData.php';
require_once 'TDS_header.php';

//if(isset($_POST["submit"])) {

    $msg = '1';

    $uid =  $_POST["uid"];

    $redirect = "http://localhost/test_data_sheets/php/TDS_testdataform.php?id=" . $uid;

    $folder = $uid . "/";
    
    $target_dir = "//CMDATASTORAGE/Workpad/Jonah B/ItemID Files/" . $folder;
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
        $msg .=  "Your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

            //retrieve exiting purchasing data from dB
            $query = "SELECT TDRM_file
            FROM TDRM
            WHERE TDRM_uid = '$uid'";

            $result = mysqli_query ( $conn, $query );

            $i = mysqli_fetch_assoc($result);

            $pDoc = $i['TDRM_file'];

            $textName .= ", " . $pDoc;
            
            $sql = "UPDATE TDRM
            SET TDRM_file = '$textName'
            WHERE TDRM_uid = '$uid'";

            $result = mysqli_query ( $conn, $sql );

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
                    <input type='button' onclick='redirect()' value='Close' class='mainBtn'>
                </div>
            </div>
        </div>
    </div>
    <script>
        function redirect(){
            window.location.replace('$redirect');
        }

        setTimeout("window.location.replace('$redirect');",3000);

    </script>
</body>
</html>

_FixedHTML2;



?>