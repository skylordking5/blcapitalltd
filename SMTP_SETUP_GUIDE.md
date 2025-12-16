# Google SMTP Setup Guide for BL Capital Website

This guide will walk you through setting up Google SMTP for your contact form email functionality.

## Prerequisites

- A Gmail account (or Google Workspace email)
- Access to your web hosting server

## Step 1: Enable 2-Factor Authentication

1. Go to your Google Account: https://myaccount.google.com/
2. Click on **Security** in the left sidebar
3. Under "How you sign in to Google", click on **2-Step Verification**
4. Follow the prompts to enable 2-Step Verification if not already enabled

## Step 2: Generate App Password

1. Still in **Security**, scroll down to "How you sign in to Google"
2. Click on **App passwords** (you'll only see this if 2-Step Verification is enabled)
3. You may need to sign in again
4. In the "Select app" dropdown, choose **Mail**
5. In the "Select device" dropdown, choose **Other (Custom name)**
6. Enter a name like "BL Capital Website"
7. Click **Generate**
8. Google will display a 16-character password. **Copy this immediately** - you won't see it again!

## Step 3: Configure SMTP Settings

1. Open the file `forms/smtp-config.php` in your website directory
2. Replace the placeholder values:

```php
define('SMTP_USERNAME', 'your-actual-email@gmail.com'); // Your Gmail address
define('SMTP_PASSWORD', 'your-16-char-app-password'); // The password from Step 2
define('RECEIVING_EMAIL', 'managementofc@blcapitalltd.com'); // Where you want to receive messages
```

3. Save the file

## Step 4: Secure Your Configuration

### For Version Control (Git)

Add the following to your `.gitignore` file:

```
forms/smtp-config.php
```

### For Production Server

1. Upload all files to your server
2. Set proper file permissions:
   ```bash
   chmod 600 forms/smtp-config.php
   ```
3. Make sure the `forms` directory is writable by PHP:
   ```bash
   chmod 755 forms
   ```

## Step 5: Test the Contact Form

1. Open your website in a browser
2. Navigate to the Contact page
3. Fill out all fields including the new phone number field
4. Submit the form
5. Check the receiving email address for the message

## Troubleshooting

### "Unable to send message" Error

**Possible causes:**
- Incorrect App Password - regenerate and update `smtp-config.php`
- 2-Factor Authentication not enabled on Google account
- SMTP ports blocked by hosting provider
- PHPMailer library not properly installed

**Solutions:**
1. Verify credentials in `smtp-config.php`
2. Enable debug mode by setting `define('SMTP_DEBUG', true);` in `smtp-config.php`
3. Check with your hosting provider if ports 587 (TLS) or 465 (SSL) are open
4. Verify PHPMailer files exist in `assets/vendor/PHPMailer/`

### "Less secure app" Warning

Google has discontinued "less secure app access". You **must** use App Passwords instead. Never use your regular Gmail password.

### Emails Going to Spam

1. Add a custom domain email address instead of Gmail (recommended for production)
2. Configure SPF and DKIM records for your domain
3. Ask recipients to whitelist your email address

### Port Issues

If port 587 doesn't work, try using port 465 with SSL:

```php
define('SMTP_PORT', 465);
define('SMTP_ENCRYPTION', 'ssl');
```

## Production Hosting Notes

### Shared Hosting (cPanel, Plesk, etc.)
- Most shared hosts support SMTP on ports 587 and 465
- Ensure PHP has `openssl` extension enabled
- PHPMailer should work out of the box

### VPS/Dedicated Server
- Ensure firewall allows outbound connections on ports 587/465
- Install PHP extensions: `php-openssl`, `php-mbstring`
- Configure PHP to allow `fsockopen()` and `stream_socket_client()`

### Alternative: Use Your Hosting Provider's SMTP
Many hosting providers offer their own SMTP servers. Update `smtp-config.php`:

```php
define('SMTP_HOST', 'mail.yourdomain.com'); // Your host's SMTP server
define('SMTP_USERNAME', 'contact@yourdomain.com');
define('SMTP_PASSWORD', 'your-email-password');
define('SMTP_PORT', 587);
```

## Security Best Practices

1. ✅ **Never commit `smtp-config.php` to version control**
2. ✅ **Use environment variables in production** (if your host supports it)
3. ✅ **Regularly rotate your App Password**
4. ✅ **Monitor your Google account for suspicious activity**
5. ✅ **Use a dedicated email account for website forms**
6. ✅ **Keep PHPMailer updated** (check for updates regularly)

## Support

If you continue to experience issues:
- Check PHP error logs on your server
- Enable SMTP debug mode temporarily
- Contact your hosting provider for SMTP configuration help
- Verify all files uploaded correctly to your server

## File Checklist

Ensure these files are present on your server:
- ✅ `contact.html` (updated with phone field)
- ✅ `forms/contact.php` (main handler)
- ✅ `forms/smtp-config.php` (your credentials)
- ✅ `assets/vendor/PHPMailer/PHPMailer.php`
- ✅ `assets/vendor/PHPMailer/SMTP.php`
- ✅ `assets/vendor/PHPMailer/Exception.php`

---

**Last Updated:** December 2025
