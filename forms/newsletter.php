<?php
/**
 * Newsletter Subscription Handler with Google SMTP
 * Supports both PHPMailer (recommended) and native PHP mail
 */

// Prevent direct access
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Direct access not permitted');
}

// Load SMTP configuration
require_once 'smtp-config.php';

// Sanitize and validate input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Get form data
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';

// Validate required field
if (empty($email)) {
    http_response_code(400);
    die('Email is required');
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
        $mail->SMTPSecure = SMTP_ENCRYPTION;
        $mail->Port = SMTP_PORT;
        
        // Recipients
        $mail->setFrom(SMTP_USERNAME, EMAIL_FROM_NAME);
        $mail->addAddress(RECEIVING_EMAIL);
        $mail->addReplyTo($email);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = EMAIL_SUBJECT_PREFIX . 'New Newsletter Subscription';
        
        // Email body
        $mail->Body = "
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
                    <h2>New Newsletter Subscription</h2>
                    <div class='field'>
                        <span class='label'>Email:</span>
                        <span class='value'>{$email}</span>
                    </div>
                    <div class='field'>
                        <span class='label'>Subscribed at:</span>
                        <span class='value'>" . date('Y-m-d H:i:s') . "</span>
                    </div>
                </div>
            </div>
        </body>
        </html>
        ";
        
        // Plain text alternative
        $mail->AltBody = "New Newsletter Subscription\n\n"
                       . "Email: {$email}\n"
                       . "Subscribed at: " . date('Y-m-d H:i:s');
        
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
    // Fallback to native PHP mail with custom headers
    
    $to = RECEIVING_EMAIL;
    $email_subject = EMAIL_SUBJECT_PREFIX . 'New Newsletter Subscription';
    
    // HTML email body
    $html_message = "
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
                <h2>New Newsletter Subscription</h2>
                <div class='field'>
                    <span class='label'>Email:</span>
                    <span class='value'>{$email}</span>
                </div>
                <div class='field'>
                    <span class='label'>Subscribed at:</span>
                    <span class='value'>" . date('Y-m-d H:i:s') . "</span>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Set headers for HTML email
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: " . EMAIL_FROM_NAME . " <" . SMTP_USERNAME . ">\r\n";
    $headers .= "Reply-To: {$email}\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    // Send email
    if (mail($to, $email_subject, $html_message, $headers)) {
        echo 'OK';
    } else {
        http_response_code(500);
        echo "Unable to send message. Please try again later.";
    }
}
?>
