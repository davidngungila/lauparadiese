<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Test Email - SMTP Configuration</title>
    <style>
        /* Reset styles */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
        }

        /* Main styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            height: 100% !important;
            background-color: #f4f4f4;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .email-wrapper {
            width: 100%;
            background-color: #f4f4f4;
            padding: 20px 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #3ea572 0%, #2d8654 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
            line-height: 1.2;
        }

        .email-header .icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: inline-block;
        }

        .email-body {
            padding: 40px 30px;
        }

        .success-badge {
            display: inline-block;
            background-color: #d4edda;
            color: #155724;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .email-body h2 {
            color: #333333;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 20px 0;
            line-height: 1.3;
        }

        .email-body p {
            color: #666666;
            font-size: 16px;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }

        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #3ea572;
            padding: 20px;
            margin: 30px 0;
            border-radius: 4px;
        }

        .info-box h3 {
            color: #333333;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 15px 0;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin: 15px 0;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            color: #666666;
            font-size: 14px;
            font-weight: 600;
            padding: 8px 15px 8px 0;
            width: 140px;
            vertical-align: top;
        }

        .info-value {
            display: table-cell;
            color: #333333;
            font-size: 14px;
            padding: 8px 0;
            vertical-align: top;
        }

        .info-value code {
            background-color: #e9ecef;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 13px;
            color: #d63384;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .email-footer p {
            color: #999999;
            font-size: 14px;
            margin: 5px 0;
        }

        .email-footer .company-name {
            color: #3ea572;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3ea572;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
        }

        .button:hover {
            background-color: #2d8654;
        }

        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                border-radius: 0 !important;
            }

            .email-header,
            .email-body,
            .email-footer {
                padding: 20px !important;
            }

            .email-header h1 {
                font-size: 24px !important;
            }

            .email-body h2 {
                font-size: 20px !important;
            }

            .info-label {
                display: block;
                width: 100%;
                padding: 8px 0 4px 0;
            }

            .info-value {
                display: block;
                padding: 0 0 8px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <div class="icon">✓</div>
                <h1>SMTP Configuration Successful!</h1>
            </div>

            <!-- Body -->
            <div class="email-body">
                <span class="success-badge">✓ Test Email Sent Successfully</span>
                
                <h2>Congratulations!</h2>
                
                <p>
                    This is a test email from <strong>Lau Paradise Adventures</strong> to verify that your SMTP configuration is working correctly.
                </p>

                <p>
                    If you're reading this email, it means your email server settings have been configured properly and emails are being sent successfully!
                </p>

                <div class="info-box">
                    <h3>📧 Email Configuration Details</h3>
                    <div class="info-grid">
                        <div class="info-row">
                            <div class="info-label">Account Name:</div>
                            <div class="info-value">{{ $accountName }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email Address:</div>
                            <div class="info-value"><code>{{ $accountEmail }}</code></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">SMTP Host:</div>
                            <div class="info-value"><code>{{ $smtpHost }}</code></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">SMTP Port:</div>
                            <div class="info-value"><code>{{ $smtpPort }}</code></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Encryption:</div>
                            <div class="info-value"><code>{{ strtoupper($smtpEncryption) }}</code></div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Test Date:</div>
                            <div class="info-value">{{ $testDate }}</div>
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <p style="color: #333333; font-weight: 600;">
                    Your email system is now ready to send professional emails to your customers, partners, and team members.
                </p>

                <p style="margin-top: 30px;">
                    <strong>Next Steps:</strong>
                </p>
                <ul style="color: #666666; line-height: 1.8; padding-left: 20px;">
                    <li>Configure email templates for automated communications</li>
                    <li>Set up email notifications for important events</li>
                    <li>Test sending emails to different recipients</li>
                    <li>Monitor email logs for delivery status</li>
                </ul>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p class="company-name">Lau Paradise Adventures</p>
                <p>This is an automated test email sent from your email configuration system.</p>
                <p style="margin-top: 15px; font-size: 12px; color: #999999;">
                    If you did not expect this email, please contact your system administrator.
                </p>
            </div>
        </div>
    </div>
</body>
</html>


