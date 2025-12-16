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

  // Extract and validate form fields
  const { name, email, phone, subject, message } = formData;

  // Validation
  if (!name || !email || !phone || !subject || !message) {
    return {
      statusCode: 400,
      body: JSON.stringify({ error: 'All fields are required' })
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
  const emailSubject = `[BL Capital Contact] ${subject}`;
  
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
          <h2>New Contact Form Submission</h2>
          <div class="field">
            <span class="label">Name:</span>
            <span class="value">${name}</span>
          </div>
          <div class="field">
            <span class="label">Email:</span>
            <span class="value">${email}</span>
          </div>
          <div class="field">
            <span class="label">Phone:</span>
            <span class="value">${phone}</span>
          </div>
          <div class="field">
            <span class="label">Subject:</span>
            <span class="value">${subject}</span>
          </div>
          <div class="field">
            <span class="label">Message:</span>
            <div class="value">${message.replace(/\n/g, '<br>')}</div>
          </div>
        </div>
      </div>
    </body>
    </html>
  `;

  const textContent = `
New Contact Form Submission

Name: ${name}
Email: ${email}
Phone: ${phone}
Subject: ${subject}

Message:
${message}
  `;

  // Email options
  const mailOptions = {
    from: `${process.env.EMAIL_FROM_NAME || 'BL Capital Website'} <${process.env.SMTP_USER}>`,
    to: process.env.RECEIVING_EMAIL,
    replyTo: `${name} <${email}>`,
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
