# Deploying BL Capital Website to Netlify

Complete guide for deploying your website to Netlify with email functionality.

## Prerequisites

- Netlify account (free): https://netlify.com
- GitHub account (optional, for automatic deployments)
- Your website files

## Deployment Methods

### Method 1: Deploy via Netlify Dashboard (Easiest)

1. **Prepare your files:**
   - Ensure all files are in `c:\BL Capital Website Revised\blcapitalltd\`
   - Verify `netlify.toml`, `package.json`, and `netlify/functions/` exist

2. **Upload to Netlify:**
   - Log in to https://app.netlify.com
   - Click "Add new site" → "Deploy manually"
   - Drag and drop your entire website folder
   - Wait for deployment (1-2 minutes)

3. **Configure Environment Variables:**
   - Go to Site settings → Environment variables
   - Add the following variables:

```
SMTP_HOST = mail.privateemail.com
SMTP_PORT = 587
SMTP_USER = managementofc@blcapitalltd.com
SMTP_PASS = nnst-hxvz-wruv-bztp
SMTP_ENCRYPTION = tls
RECEIVING_EMAIL = managementofc@blcapitalltd.com
EMAIL_FROM_NAME = BL Capital Website
```

4. **Redeploy:**
   - After adding environment variables, go to Deploys
   - Click "Trigger deploy" → "Clear cache and deploy site"

5. **Test:**
   - Visit your live site URL
   - Test contact form
   - Test newsletter subscription

---

### Method 2: Deploy via GitHub (Recommended for Updates)

1. **Create GitHub Repository:**
   ```bash
   cd "c:\BL Capital Website Revised\blcapitalltd"
   git init
   git add .
   git commit -m "Initial commit - Netlify ready"
   ```

2. **Push to GitHub:**
   - Create a new repository on GitHub
   - Follow GitHub's instructions to push your code

3. **Connect to Netlify:**
   - Log in to Netlify
   - Click "Add new site" → "Import an existing project"
   - Choose "GitHub"
   - Select your repository
   - Build settings will be auto-detected from `netlify.toml`
   - Click "Deploy site"

4. **Configure Environment Variables** (same as Method 1)

5. **Automatic Deploys:**
   - Now whenever you push to GitHub, Netlify auto-deploys

---

## Environment Variables Explained

| Variable | Value | Purpose |
|----------|-------|---------|
| `SMTP_HOST` | `mail.privateemail.com` | Your PrivateEmail SMTP server |
| `SMTP_PORT` | `587` | SMTP port (TLS) |
| `SMTP_USER` | `managementofc@blcapitalltd.com` | Your email address |
| `SMTP_PASS` | `nnst-hxvz-wruv-bztp` | Your app password |
| `SMTP_ENCRYPTION` | `tls` | Encryption type |
| `RECEIVING_EMAIL` | `managementofc@blcapitalltd.com` | Where to receive messages |
| `EMAIL_FROM_NAME` | `BL Capital Website` | Sender name in emails |

> **IMPORTANT**: Never commit these values to Git. They're only stored in Netlify's secure environment.

---

## Testing Your Deployment

### 1. Test Contact Form

1. Visit: `https://your-site-name.netlify.app/contact.html`
2. Fill out all fields:
   - Name
   - Email
   - Phone
   - Subject
   - Message
3. Click "Send Message"
4. You should see "Your message has been sent. Thank you!"
5. Check `managementofc@blcapitalltd.com` for the email

### 2. Test Newsletter

1. Scroll to any page footer
2. Enter email address
3. Click "Subscribe"
4. You should see "Your subscription request has been sent. Thank you!"
5. Check inbox for subscription notification

---

## Custom Domain Setup (Optional)

1. **In Netlify:**
   - Go to Site settings → Domain management
   - Click "Add custom domain"
   - Enter: `blcapitalltd.com` (or your domain)

2. **In Namecheap (or your domain provider):**
   - Go to Advanced DNS
   - Add these records:
     ```
     Type: A Record
     Host: @
     Value: 75.2.60.5
     
     Type: CNAME
     Host: www
     Value: your-site.netlify.app
     ```

3. **Enable HTTPS:**
   - Netlify automatically provides free SSL
   -Wait 24-48 hours for DNS propagation

---

## Troubleshooting

### Forms Not Submitting

**Symptom:** "Network error" when submitting forms

**Solutions:**
1. Check browser console for errors (F12)
2. Verify environment variables are set in Netlify
3. Check Functions log in Netlify dashboard
4. Ensure `package.json` dependencies installed

### Emails Not Received

**Symptom:** Form submits successfully but no email arrives

**Solutions:**
1. Check spam/junk folder
2. Verify `SMTP_PASS` is correct in environment variables
3. Check Netlify Function logs for errors
4. Ensure PrivateEmail account is active
5. Try using port `465` with `ssl` instead of `587` with `tls`

### Function Errors

**Symptom:** "500 Internal Server Error"

**Solutions:**
1. Check Netlify Functions log:
   - Go to Functions tab in Netlify
   - Click on `contact` or `newsletter`
   - View logs
2. Verify all environment variables are set
3. Ensure nodemailer is installed (check package.json)

---

## File Structure

Your deployed Netlify site:

```
blcapitalltd/
├── netlify/
│   └── functions/
│       ├── contact.js        # Contact form handler
│       └── newsletter.js     # Newsletter handler
├── assets/
│   └── js/
│       └── newsletter-handler.js  # Client-side script
├── *.html                    # All your HTML pages
├── package.json              # Dependencies
├── netlify.toml              # Netlify configuration
└── .gitignore               # Excludes node_modules, .env
```

---

## Maintenance

### Updating Content

**Via Netlify Dashboard:**
1. Make changes locally
2. Drag and drop updated folder to Netlify

**Via GitHub:**
1. Make changes locally
2. Commit and push to GitHub
3. Netlify auto-deploys

### Monitoring

- Check Netlify analytics for form submissions
- Monitor email inbox for messages
- Review Functions logs for errors

---

## Cost

- **Netlify Free Tier:**
  - 100 GB bandwidth/month
  - 300 build minutes/month
  - 125,000 serverless function requests/month
  
**This is more than enough for most small business websites!**

---

## Support

If you encounter issues:

1. **Check Netlify Docs:** https://docs.netlify.com
2. **Check Function Logs:** Netlify Dashboard → Functions
3. **Test Locally:** Install Netlify CLI and run `netlify dev`
4. **Contact Support:** Netlify has great community support

---

**Your website is ready to deploy! 🚀**
