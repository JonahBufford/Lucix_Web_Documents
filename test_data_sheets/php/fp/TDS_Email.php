<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'PHPMailer-master2/src/Exception.php';
require 'PHPMailer-master2/src/PHPMailer.php';
require 'PHPMailer-master2/src/SMTP.php';
require_once 'connections.php';

$id = $_POST['tdrmid'];
$name = $_POST['name'];

$query = "SELECT emailAdd
          FROM tblEmployees
          WHERE strEmpName = '$name'";

$result = odbc_exec($connOLTP, $query);

if (odbc_num_rows ( $result ) > 0) { 
	$email = odbc_result ( $result, 1 );
}

$link = "http://localhost/test_data_sheets/php/TDS_testdataform.php?id=" . "$id";

//send email
$email1 = "$email";
$email2 = '';
$subject = 'Test Data Review Required';
$body = "<img style='height:20px' src='cid:logo_2u'><p>You have been added as a reviewer for Test Data Review Sheet <a href='$link'>#$id</a></p>";


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
    $mail->addAddress($email1);     // Add a recipient
    
    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $body;
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();

    echo "$email";

} catch (Exception $e) {
    
    echo "email failed to send.";
}


?>