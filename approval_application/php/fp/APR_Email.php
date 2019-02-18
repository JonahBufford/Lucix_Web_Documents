<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'PHPMailer-master2/src/Exception.php';
require 'PHPMailer-master2/src/PHPMailer.php';
require 'PHPMailer-master2/src/SMTP.php';
require_once 'connections.php';

$id = $_POST['id'];
$notif = $_POST['notif'];
$status = $_POST['status'];

$link = "http://localhost/Approval_Application/php/APR_approvalApplication.php?id=" . "$id";

$category = "APR_app_" . $notif;

if(isset($_POST['order'])){
    $order = $_POST['order'];
    $orderQuery = "SELECT *
                   FROM APR_app
                   WHERE APR_app_order = '$order' AND APR_app_sheetID = '$id' AND APR_app_dateSigned = '0000-00-00'";

    $orderResult = mysqli_query($conn, $orderQuery);

    if(mysqli_num_rows($orderResult) == 0) {
        $order += 1;
        $listQuery = "SELECT *
                      FROM APR_app
                      WHERE $category = 1 AND APR_app_sheetID = '$id' AND APR_app_order = '$order'";

        echo $listQuery;
    }
    else{
        while($i = mysqli_fetch_assoc($orderResult)){
            $uid = $i['APR_app_uid'];
            echo $uid;
        }
    }
}
else{
    $listQuery = "SELECT *
                  FROM APR_app
                  WHERE $category = 1 AND APR_app_sheetID = '$id'";
}

$listResult = mysqli_query($conn,$listQuery);

if($status == "Pending") {
    if($notif == "notifAvailable"){
        $text = "Approval Sheet <a href='$link'>#$id</a> is ready for you to approve";
    }
    else{
        $text = "Approval Sheet <a href='$link'>#$id</a> has been created";
    }
}

else if($status == "Approved") {
    $text = "Approval Sheet <a href='$link'>#$id</a> has been approved";
}

else if($status == "Canceled") {
    $text = "Approval Sheet <a href='$link'>#$id</a> has been canceled";
}

$email = array();
while($i = mysqli_fetch_assoc($listResult)){
    $name = $i['APR_app_name'];

    $query = "SELECT emailAdd
              FROM tblEmployees
              WHERE strEmpName = '$name'";
    
    $result = odbc_exec($connOLTP, $query);
    
    if (odbc_num_rows ( $result ) > 0) { 
        $email1 = odbc_result ( $result, 1 );
        $email[] = odbc_result ( $result, 1 );
        echo $email1;
    }
}



//send email
$subject = 'Test Data Review Required';
$body = "<img style='height:20px' src='cid:logo_2u'><p>$text</p>";


$mail = new PHPMailer;                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 1;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    //$mail->Host = gethostbyname('webmail.lucix.com');
    $mail->Host = 'CMEXCHANGEFE.lucix.local';  // Specify main and backup SMTP servers
    $mail->Username = 'lwa@lucix.com';                 // SMTP username
    $mail->Password = 'Passw0rd!';                           // SMTP password
    $mail->Port = 25;                                    // TCP port to connect to 
    $mail->SMTPAuth = false;    
    $mail->AddEmbeddedImage('../../img/TDS_LucixLightBack.png', 'logo_2u');
    
    //Recipients
    $mail->setFrom('lwa@lucix.com', 'Lucix Web Applications');
    foreach($email as $address){
        $mail->addAddress($address);     // Add a recipient
    }
    
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    echo 1;

} catch (Exception $e) {
    
    echo "email failed to send.";
}


?>