<?php

require_once 'connections.php';

$user = $_POST['user'];

if(isset($_POST['id'])){
    $id = $_POST['id'];
    $submit = "<input type='submit' value='Upload File' name='submit' class='pBtn' onclick='buildProcessingForm($id)' id='upBtn' hidden>";
    $button = "";

    $dwgList = "<table class='table2' id='files'>
                    <tr>
                        <th>Title</th>
                        <th>Link</th>
                        <th>Added By</th>
                    </tr>";
                    
    $file1 = "//CMDATASTORAGE/Workpad/Jonah B/APR_Files/" . $id . "/*.pdf";
                    
    foreach ( glob ( "$file1" ) as $filename ) {

        $fileQuery = "SELECT *
                      FROM APR_files
                      WHERE APR_files_path = '$filename'";

        $fileResult = mysqli_query($conn,$fileQuery);

        $fileDetails = mysqli_fetch_assoc($fileResult);

        $title = $fileDetails['APR_files_title'];
        $addedBy = $fileDetails['APR_files_addedBy'];
        $nameOfFile = $fileDetails['APR_files_name'];

        $dwgList .= "<tr>
                        <td>$title</td>
                        <td><a style='color:blue' href='fp/APR_openDwg.php?file=" . $filename . "' target='_blank'>$nameOfFile</a></td>
                        <td>$addedBy</td>
                    </tr>";
    }

    $dwgList .= "</table>";
}

else{
    $id = "";
    $submit = "";
    $button = "<button onclick='addFileSlot()'>Add Another File</button>";
    $dwgList = "";
}

$form = "<form action='fp/APR_itemFileUpload.php' method='post' enctype='multipart/form-data' target='_self' id='form'>
            <div class='titleRow'>Select file to upload:</div>
            <div id='fileInputs'>
                <input type='file' name='fileToUpload1' id='fileToUpload1' class='choseBtn' onclick='showUpBtn()'>
                Title <input type='text' name='title1'>
                <br>
            </div>
            $submit
            <input type='hidden' value='$id' name='uid' id='uid'>
            <input type='hidden' value='$user' name='user'>
            <input type='hidden' value='1' name='fileCounter'>
        </form>
        $button
        $dwgList";

echo $form;

?>