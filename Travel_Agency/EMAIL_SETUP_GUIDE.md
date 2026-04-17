# Email System Setup Guide

## Overview
This travel agency booking system now includes automatic email notifications using PHPMailer. Users will receive:
1. **Thank You Email** - Immediately after booking
2. **Payment Confirmation Email** - After successful payment

## Installation Steps

### 1. Install PHPMailer
Run the auto-install script:
```
http://localhost/Travel_Agency/install_phpmailer.php
```

OR manually install using Composer:
```bash
composer require phpmailer/phpmailer
```

### 2. Configure Gmail SMTP
Edit `config/mail.php` and update these settings:

```php
$this->mail->Username   = 'your-email@gmail.com';              // Your Gmail address
$this->mail->Password   = 'your-app-password';                 // Your Gmail app password
```

### 3. Enable Gmail App Password
1. Go to [Google Account Settings](https://myaccount.google.com/)
2. Enable **2-Step Verification**
3. Go to **Security** > **App Passwords**
4. Generate a new app password for "Mail"
5. Use this app password in the configuration

## Email Templates

### Thank You Email Features:
- Beautiful HTML design with responsive layout
- Booking details (ID, package, dates, amount)
- Call-to-action button to complete payment
- Contact information

### Payment Confirmation Email Features:
- Payment success confirmation
- Complete booking details
- Payment method information
- Important travel instructions

## Testing the System

### Test Booking Flow:
1. Register/login as a user
2. Select a package and complete booking
3. Check your email for thank you message
4. Complete payment
5. Check your email for payment confirmation

### Debug Mode:
Email errors are logged to PHP error log. Check:
```bash
# XAMPP error log location
C:\xampp\apache\logs\error.log
```

## Alternative SMTP Providers

You can use other email providers by updating the SMTP settings:

### Outlook/Hotmail:
```php
$this->mail->Host       = 'smtp-mail.outlook.com';
$this->mail->Port       = 587;
$this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
```

### SendGrid:
```php
$this->mail->Host       = 'smtp.sendgrid.net';
$this->mail->Port       = 587;
$this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
```

## Security Notes

- Never commit email credentials to version control
- Use environment variables for production
- Enable app passwords instead of regular passwords
- Monitor email sending limits

## Troubleshooting

### Common Issues:

1. **"SMTP Error: Could not authenticate"**
   - Check Gmail app password
   - Enable 2-step verification
   - Verify "Less secure app access" is ON

2. **"Connection timed out"**
   - Check firewall settings
   - Verify SMTP port (465 for SSL, 587 for TLS)

3. **Email not sending but no error**
   - Check spam folder
   - Verify sender email address
   - Check email quota limits

## Production Deployment

For production use:
1. Use transactional email services (SendGrid, Mailgun)
2. Set up proper DNS records (SPF, DKIM)
3. Monitor email deliverability
4. Set up bounce handling

## Features Included

- **Responsive HTML emails** with modern design
- **Text-only fallback** for email clients
- **Error handling** with graceful degradation
- **Security** with input sanitization
- **Logging** for debugging
- **Personalization** with user names and booking details

The email system enhances user experience by providing immediate confirmation and professional communication throughout the booking process.
