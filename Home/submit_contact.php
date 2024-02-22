<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';
require_once '../Auth/config.php';
require_once 'Contact.php';


function sendEmail($name, $email, $message) {
    $mail = new PHPMailer(true);
    try {
        
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'chediouerghi8@gmail.com'; 
        $mail->Password = 'wjuu ykpk vukz jyax'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        
        $mail->setFrom('chediouerghi8@gmail.com', 'PHP'); 
        $mail->addAddress('chediouerghi8@gmail.com'); 
        $mail->addReplyTo($email, $name); 

        
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission';
        $mail->Body = "Name: $name<br>Email: $email<br>Message: $message";

        
        $mail->send();
        echo 'Message sent successfully.';
    } catch (Exception $e) {
        echo "Error: Message not sent. {$mail->ErrorInfo}";
    }
}


if (isset($_POST['submit'])) {
    
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    
    $contact = new Contact($name, $email, $message);

    
    $result = $contact->insertIntoDatabase($conn);

    
    if ($result) {
        
        sendEmail($name, $email, $message);
    } else {
        echo "Error: Message not sent.";
    }

    
    $conn->close();
} else {
    
    header("Location: contact.html");
    exit();
}
?>
