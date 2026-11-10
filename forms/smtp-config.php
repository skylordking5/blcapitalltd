<?php
/**
 * SMTP Configuration File
 * 
 * IMPORTANT SECURITY NOTES:
 * 1. Add this file to .gitignore to prevent committing credentials
 * 2. Never share this file publicly
 * 3. Use environment variables in production for better security
 * 
 * SETUP INSTRUCTIONS:
 * 1. Go to your Google Account: https://myaccount.google.com/
 * 2. Navigate to Security > 2-Step Verification (enable if not already)
 * 3. Go to Security > App passwords
 * 4. Generate a new app password for "Mail" on "Other (Custom name)"
 * 5. Copy the 16-character password and paste it below
 * 6. Fill in your Gmail address
 */

// Email account that will SEND the emails
define('SMTP_USERNAME', 'managementofc@blcapitalltd.com'); // Replace with your Gmail address
define('SMTP_PASSWORD', 'nnst-hxvz-wruv-bztp'); // Replace with your Google App Password (16 characters, no spaces)

// Email account that will RECEIVE the contact form submissions
define('RECEIVING_EMAIL', 'managementofc@blcapitalltd.com'); // Email where messages will be sent

// SMTP Server Settings (Gmail - usually no need to change these)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587); // Use 587 for TLS or 465 for SSL
define('SMTP_ENCRYPTION', 'tls'); // Use 'tls' for port 587 or 'ssl' for port 465

// Email Settings
define('EMAIL_FROM_NAME', 'BL Capital Website'); // Name that appears as sender
define('EMAIL_SUBJECT_PREFIX', '[BL Capital Contact] '); // Prefix for email subjects

// Debug mode (set to false in production)
define('SMTP_DEBUG', false); // Set to true to see detailed error messages
?>
