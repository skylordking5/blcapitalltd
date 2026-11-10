# How to Use PrivateEmail by Namecheap for Contact Form

## Step 1: Get Your PrivateEmail SMTP Settings

1. **Log in to Namecheap:** https://www.namecheap.com/
2. Go to **Dashboard** → **PrivateEmail**
3. Click on your email account (`managementofc@blcapitalltd.com`)
4. Look for **Mail Settings** or **SMTP Configuration**

## Step 2: PrivateEmail SMTP Settings

**Standard PrivateEmail SMTP Configuration:**

```
SMTP Server: mail.privateemail.com
SMTP Port: 587 (TLS) or 465 (SSL)
Username: managementofc@blcapitalltd.com
Password: Your PrivateEmail password
Encryption: TLS (for port 587) or SSL (for port 465)
```

## Step 3: Update smtp-config.php

Open `forms/smtp-config.php` and update these lines:

```php
// Your PrivateEmail account
define('SMTP_USERNAME', 'managementofc@blcapitalltd.com');
define('SMTP_PASSWORD', 'your-privateemail-password'); // Your actual password

// Email where you want to receive messages
define('RECEIVING_EMAIL', 'managementofc@blcapitalltd.com');

// PrivateEmail SMTP Settings
define('SMTP_HOST', 'mail.privateemail.com');
define('SMTP_PORT', 587); // Use 587 for TLS or 465 for SSL
define('SMTP_ENCRYPTION', 'tls'); // Use 'tls' for port 587 or 'ssl' for port 465
```

## Step 4: Upload to Your Hosting

1. Upload all website files to your hosting server
2. The contact form will now send emails using your PrivateEmail account
3. Messages will be received at `managementofc@blcapitalltd.com`

## Important Notes

✅ **Use Your Regular Password:** Unlike Gmail, you use your regular PrivateEmail password (not an App Password)

✅ **Professional Appearance:** Emails will come from your business domain `@blcapitalltd.com`

✅ **More Reliable:** PrivateEmail is better for business emails than Gmail

## Troubleshooting

### If emails don't send:

1. **Verify credentials** in `smtp-config.php`
2. **Check SMTP port:** Try 465 with SSL if 587 doesn't work
3. **Verify password:** Make sure you're using the correct PrivateEmail password
4. **Check hosting:** Ensure your host allows outbound SMTP connections

### Enable Debug Mode

If you need to see detailed error messages:

```php
// In forms/smtp-config.php
define('SMTP_DEBUG', true);
```

## Alternative: Find Settings in Namecheap

If you can't find the SMTP settings:

1. Log into Namecheap
2. Go to **PrivateEmail** dashboard
3. Click **"Manage" or "Settings"** for your email
4. Look for **"Server Settings"** or **"Mail Client Setup"**
5. Copy the SMTP server, port, and encryption type

---

**Once configured, your contact form will work perfectly on any PHP hosting server!**
