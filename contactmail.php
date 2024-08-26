<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = !empty($_POST['full_name']) ? htmlspecialchars(trim($_POST['full_name'])) : '';
    $first_name = !empty($_POST['first_name']) ? htmlspecialchars(trim($_POST['first_name'])) : '';
    $middle_name = !empty($_POST['middle_name']) ? htmlspecialchars(trim($_POST['middle_name'])) : '';
    $last_name = !empty($_POST['last_name']) ? htmlspecialchars(trim($_POST['last_name'])) : '';
    $email = !empty($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $subject = !empty($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $message = !empty($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
    $contact_no = !empty($_POST['contact_no']) ? htmlspecialchars(trim($_POST['contact_no'])) : '';
    $date = !empty($_POST['date']) ? htmlspecialchars(trim($_POST['date'])) : '';
    $time = !empty($_POST['time']) ? htmlspecialchars(trim($_POST['time'])) : '';

    // Check if full name is empty and concatenate parts to form full name
    if ($full_name == '') {
        $full_name = trim($first_name . ' ' . $middle_name . ' ' . $last_name);
    }

    // Validate required fields
    if (empty($full_name) || empty($email) || empty($message)) {
        echo 'Full Name, Email, and Message are required fields.';
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Invalid email format.';
        exit;
    }

    // Determine the type of form submitted and prepare the email content
    $sendMessage = $mailSubject = '';
    if (isset($_POST['form_type']) && $_POST['form_type'] == 'contact') {
        $mailSubject = 'Contact Details';
        $sendMessage = "<p>Hello,</p>
        <p>{$full_name} has sent a message:</p>
        <p><strong>Phone No:</strong> {$contact_no}</p>
        <p><strong>Email id:</strong> {$email}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Query is:</strong> {$message}</p>";
    } elseif (isset($_POST['form_type']) && $_POST['form_type'] == 'inquiry') {
        $mailSubject = 'Inquiry Details';
        $sendMessage = "<p>Hello,</p>
        <p>{$full_name} has sent an inquiry:</p>
        <p><strong>Email id:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$contact_no}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Date:</strong> {$date}</p>
        <p><strong>Time:</strong> {$time}</p>
        <p><strong>Message:</strong> {$message}</p>";
    }

    // If the message content is not empty, proceed to send the email
    if (!empty($sendMessage)) {
        $fromEmail = 'optima.solution@gmail.com';
        $toEmail = 'optima.multisinergi@gmail.com';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: {$fromEmail}" . "\r\n";

        // Attempt to send the email
        if (mail($toEmail, $mailSubject, $sendMessage, $headers)) {
            echo 'Email sent successfully';
        } else {
            echo 'Failed to send email';
        }
    } else {
        echo 'No message content to send';
    }
} else {
    echo 'No data submitted';
}
?>
