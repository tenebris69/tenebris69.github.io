<?php
// Configure your Subject Prefix and Recipient here
$subjectPrefix = '[Contact via Non website]';
$emailTo       = 'YOUR EMSIL HERE';
$errors = array(); // array to hold validation errors
$data   = array(); // array to pass back data
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = stripslashes(trim($_POST['fname']));
    $email   = stripslashes(trim($_POST['femail']));
    $subject   = stripslashes(trim($_POST['fsubject']));
    $message = stripslashes(trim($_POST['fmessage']));
    if (empty($name)) {
        $errors['fname'] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['femail'] = 'Email is invalid.';
    }
	if (empty($subject)) {
        $errors['fsubject'] = 'Subject  is required.';
    }
    if (empty($message)) {
        $errors['fmessage'] = 'Message is required.';
    }
    // if there are any errors in our errors array, return a success boolean or false
    if (!empty($errors)) {
        $data['success'] = false;
        $data['errors']  = $errors;
    } else {
        $subject = "$subjectPrefix $subject";
        $body    = '
            <strong>Name: </strong>'.$name.'<br /><br />
            <strong>Email: </strong>'.$email.'<br /><br />
            <strong>Subject: </strong>'.$subject.'<br /><br />
            <strong>Message: </strong>'.nl2br($message).'<br />
        ';
        $headers  = "MIME-Version: 1.1" . PHP_EOL;
        $headers .= "Content-type: text/html; charset=utf-8" . PHP_EOL;
        $headers .= "Content-Transfer-Encoding: 8bit" . PHP_EOL;
        $headers .= "Date: " . date('r', $_SERVER['REQUEST_TIME']) . PHP_EOL;
        $headers .= "Message-ID: <" . $_SERVER['REQUEST_TIME'] . md5($_SERVER['REQUEST_TIME']) . '@' . $_SERVER['SERVER_NAME'] . '>' . PHP_EOL;
        $headers .= "From: " . "=?UTF-8?B?".base64_encode($name)."?=" . "<$email>" . PHP_EOL;
        $headers .= "Return-Path: $emailTo" . PHP_EOL;
        $headers .= "Reply-To: $email" . PHP_EOL;
        $headers .= "X-Mailer: PHP/". phpversion() . PHP_EOL;
        $headers .= "X-Originating-IP: " . $_SERVER['SERVER_ADDR'] . PHP_EOL;
        mail($emailTo, "=?utf-8?B?" . base64_encode($subject) . "?=", $body, $headers);
        $data['success'] = true;
        $data['message'] = 'Congratulations. Your message has been sent successfully';
    }
    // return all our data to an AJAX call
//    echo json_encode($data);
	// Die with a success message
//die("<span class='success'>Success! Your message has been sent.</span>");
	
             //do your validation or something here
         header("location:index.html");

}
