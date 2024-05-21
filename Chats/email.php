<?php

// Usage example
if (isset($_POST['send'])) {
    if(!empty($_POST['subject']) && !empty($_POST['message']) && !empty($_POST['email'])) {
        $subject = $_POST['subject'];
        $message = $_POST['message'];
        $email = $_POST['email'];

        $header = "From: noreply@example.com" . "\r\n";
        $header = "Reply-To: noreply@example.com" . "\r\n";
        $header = "X-Mailer: PHP/" . phpversion();
        $mail = @mail($email, $subject, $message, $header);
        if($mail){
            echo "<h4>Mail Sent</h4>";
        } else {
            echo "<h4>Mail not sent</h4>";
        }
    }
}