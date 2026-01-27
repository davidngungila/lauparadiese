<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Backup Notification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f4f4f4;
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
        .info-row {
            display: table;
            width: 100%;
            margin: 10px 0;
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
            <div class="email-header">
                <div class="icon">💾</div>
                <h1>Database Backup Created</h1>
            </div>

            <div class="email-body">
                <span class="success-badge">✓ Backup Successful</span>
                
                <h2>Your database backup is ready!</h2>
                
                <p>
                    A new database backup has been created successfully and is attached to this email.
                </p>

                <div class="info-box">
                    <div class="info-row">
                        <div class="info-label">Backup File:</div>
                        <div class="info-value"><code>{{ $filename }}</code></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">File Size:</div>
                        <div class="info-value"><strong>{{ $fileSizeFormatted }}</strong></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Database:</div>
                        <div class="info-value"><code>{{ $databaseName }}</code></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Created At:</div>
                        <div class="info-value">{{ $backupDate }}</div>
                    </div>
                </div>

                <p style="color: #333333; font-weight: 600;">
                    The backup file is attached to this email. Please save it in a secure location.
                </p>

                <p style="margin-top: 30px;">
                    <strong>Important:</strong>
                </p>
                <ul style="color: #666666; line-height: 1.8; padding-left: 20px;">
                    <li>Store this backup in a secure, off-site location</li>
                    <li>Keep multiple backup copies for redundancy</li>
                    <li>Test your backups regularly to ensure they can be restored</li>
                    <li>This backup contains all your database data</li>
                </ul>
            </div>

            <div class="email-footer">
                <p class="company-name">Lau Paradise Adventures</p>
                <p>This is an automated backup notification from your database backup system.</p>
                <p style="margin-top: 15px; font-size: 12px; color: #999999;">
                    Backup generated automatically on {{ $backupDate }}
                </p>
            </div>
        </div>
    </div>
</body>
</html>










