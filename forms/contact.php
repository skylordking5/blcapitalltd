<?php
/**
 * Contact Form Handler
 */

// Load SMTP configuration
require_once 'smtp-config.php';

// Allow cross-origin requests (optional, mostly for local dev)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

// Clean input
function sanitize_input($data) {
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}

// Get form data
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : '';
$message = isset($_POST['message']) ? sanitize_input($_POST['message']) : '';

// 10DLC Compliance: Capture SMS Consent
$sms_consent = isset($_POST['sms_consent']) ? 'Yes' : 'No';

// Validate required fields
if (empty($name) || empty($email) || empty($phone) || empty($subject) || empty($message)) {
    http_response_code(400);
    die('All fields are required');
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die('Invalid email format');
}

// Check if PHPMailer is available
$phpmailer_path = '../assets/vendor/PHPMailer/PHPMailer.php';
$use_phpmailer = file_exists($phpmailer_path);

if ($use_phpmailer) {
    // Use PHPMailer for reliable SMTP
    require_once $phpmailer_path;
    require_once '../assets/vendor/PHPMailer/SMTP.php';
    require_once '../assets/vendor/PHPMailer/Exception.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
    try {
        $mail = new PHPMailer(true);
        
        // Server settings
        if (SMTP_DEBUG) {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        }
        
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = (SMTP_ENCRYPTION === 'ssl') ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;
        
        // Recipients
        $mail->setFrom(SMTP_USERNAME, EMAIL_FROM_NAME);
        $mail->addAddress(RECEIVING_EMAIL);
        $mail->addReplyTo($email, $name);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = EMAIL_SUBJECT_PREFIX . ' ' . $subject;
        
        $mail->Body = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { padding: 20px; background-color: #f4f4f4; }
                .content { background-color: white; padding: 20px; border-radius: 5px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #555; }
                .value { color: #333; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='content'>
                    <h2>New Contact Form Submission</h2>
                    <div class='field'>
                        <span class='label'>Name:</span>
                        <span class='value'>{$name}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Email:</span>
                        <span class='value'>{$email}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Phone:</span>
                        <span class='value'>{$phone}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>SMS Opt-In:</span>
                        <span class='value'>{$sms_consent}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Subject:</span>
                        <span class='value'>{$subject}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Message:</span>
                        <div class='value'>" . nl2br($message) . "</div>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Plain text alternative
        $mail->AltBody = "New Contact Form Submission\n\n"
                       . "Name: {$name}\n"
                       . "Email: {$email}\n"
                       . "Phone: {$phone}\n"
                       . "SMS Opt-In: {$sms_consent}\n"
                       . "Subject: {$subject}\n\n"
                       . "Message:\n{$message}";
        
        $mail->send();
        echo 'OK';
        
    } catch (Exception $e) {
        http_response_code(500);
        if (SMTP_DEBUG) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        } else {
            echo "Unable to send message. Please try again later.";
        }
    }
} else {
    // Fallback if PHPMailer is missing (not recommended for Gmail/SMTP)
    $to = RECEIVING_EMAIL;
    $email_subject = EMAIL_SUBJECT_PREFIX . ' ' . $subject;
    
    $headers = "From: " . EMAIL_FROM_NAME . " <" . SMTP_USERNAME . ">\r\n";
    $headers .= "Reply-To: $name <$email>\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    $body = "
    <h2>New Contact Form Submission</h2>
    <p><strong>Name:</strong> {$name}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Phone:</strong> {$phone}</p>
    <p><strong>SMS Opt-In:</strong> {$sms_consent}</p>
    <p><strong>Subject:</strong> {$subject}</p>
    <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
    ";
    
    if (mail($to, $email_subject, $body, $headers)) {
        echo 'OK';
    } else {
        http_response_code(500);
        echo "Unable to send message. Please try again later.";
    }
}
?>
