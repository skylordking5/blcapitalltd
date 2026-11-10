const nodemailer = require('nodemailer');

exports.handler = async (event) => {
    // Only allow POST requests
    if (event.httpMethod !== 'POST') {
        return {
            statusCode: 405,
            body: JSON.stringify({ error: 'Method not allowed' })
        };
    }

    // Parse form data
    let formData;
    try {
        formData = JSON.parse(event.body);
    } catch (e) {
        // Handle URL-encoded form data
        const params = new URLSearchParams(event.body);
        formData = Object.fromEntries(params);
    }

    // Extract and validate email
    const { email } = formData;

    // Validation
    if (!email) {
        return {
            statusCode: 400,
            body: JSON.stringify({ error: 'Email is required' })
        };
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        return {
            statusCode: 400,
            body: JSON.stringify({ error: 'Invalid email format' })
        };
    }

    // Get SMTP configuration from environment variables
    const smtpConfig = {
        host: process.env.SMTP_HOST || 'mail.privateemail.com',
        port: parseInt(process.env.SMTP_PORT) || 587,
        secure: process.env.SMTP_ENCRYPTION === 'ssl',
        auth: {
            user: process.env.SMTP_USER,
            pass: process.env.SMTP_PASS
        }
    };

    // Create transporter
    const transporter = nodemailer.createTransport(smtpConfig);

    // Email content
    const emailSubject = '[BL Capital Contact] New Newsletter Subscription';

    const currentDate = new Date().toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });

    const htmlContent = `
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
      <div class="container">
        <div class="content">
          <h2>New Newsletter Subscription</h2>
          <div class="field">
            <span class="label">Email:</span>
            <span class="value">${email}</span>
          </div>
          <div class="field">
            <span class="label">Subscribed at:</span>
            <span class="value">${currentDate}</span>
          </div>
        </div>
      </div>
    </body>
    </html>
  `;

    const textContent = `
New Newsletter Subscription

Email: ${email}
Subscribed at: ${currentDate}
  `;

    // Email options
    const mailOptions = {
        from: `${process.env.EMAIL_FROM_NAME || 'BL Capital Website'} <${process.env.SMTP_USER}>`,
        to: process.env.RECEIVING_EMAIL,
        replyTo: email,
        subject: emailSubject,
        text: textContent,
        html: htmlContent
    };

    // Send email
    try {
        await transporter.sendMail(mailOptions);

        return {
            statusCode: 200,
            headers: {
                'Content-Type': 'application/json',
                'Access-Control-Allow-Origin': '*'
            },
            body: JSON.stringify({
                success: true,
                message: 'OK'
            })
        };
    } catch (error) {
        console.error('Email send error:', error);

        return {
            statusCode: 500,
            headers: {
                'Content-Type': 'application/json',
                'Access-Control-Allow-Origin': '*'
            },
            body: JSON.stringify({
                error: 'Unable to send message. Please try again later.',
                details: process.env.SMTP_DEBUG === 'true' ? error.message : undefined
            })
        };
    }
};
