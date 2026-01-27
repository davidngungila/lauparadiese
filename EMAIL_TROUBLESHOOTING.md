# Email Troubleshooting Guide

## Issue: Email Not Appearing in Gmail

### Quick Checks:

1. **Check Spam/Junk Folder**
   - Gmail may have filtered the email
   - Look in "Spam" or "All Mail" folders

2. **Wait a Few Minutes**
   - Gmail can delay emails by 1-5 minutes
   - Check again after waiting

3. **Verify Email Configuration**
   - Run: `php artisan email:test-send davidngungila@gmail.com --v`
   - This will show detailed configuration

4. **Check Gmail Security Settings**
   - Ensure 2-Factor Authentication is enabled
   - Generate an App Password (not regular password)
   - App Password format: `xxxx xxxx xxxx xxxx` (16 characters)

5. **Verify App Password**
   - Go to: https://myaccount.google.com/apppasswords
   - Make sure you're using the App Password, not your regular Gmail password
   - Current App Password: `cykk ionu mmil lusd`

### Current Configuration:
- **SMTP Host:** smtp.gmail.com
- **Port:** 587
- **Encryption:** TLS
- **Username:** lauparadiseadventure@gmail.com
- **From Email:** lauparadiseadventure@gmail.com

### Test Commands:

```bash
# Test email sending with diagnostics
php artisan email:test-send davidngungila@gmail.com --v

# Reconfigure email account
php artisan email:configure-account --test-email=davidngungila@gmail.com
```

### Common Issues:

1. **"Less Secure App Access" Error**
   - Gmail no longer supports "Less Secure Apps"
   - Solution: Use App Password instead

2. **Authentication Failed**
   - Verify App Password is correct
   - Ensure no extra spaces in password
   - Current password: `cykk ionu mmil lusd`

3. **Connection Timeout**
   - Check firewall settings
   - Ensure port 587 is not blocked
   - Try port 465 with SSL instead

4. **Email in Spam**
   - Gmail may mark first emails as spam
   - Mark as "Not Spam" to improve delivery

### Next Steps:

1. Check Gmail account: https://mail.google.com
2. Check Spam folder
3. Search for: "Lau Paradise Adventures" or "SMTP Configuration"
4. Wait 5 minutes and check again
5. Run test command again if needed

### Verification:

The email should have:
- **Subject:** "✓ Test Email - SMTP Configuration Successful | Lau Paradise Adventures"
- **From:** lauparadiseadventure@gmail.com
- **Content:** Beautiful HTML email with configuration details

If email still doesn't appear after 10 minutes, there may be a Gmail delivery issue or the email was blocked.


