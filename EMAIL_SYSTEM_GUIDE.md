# 📧 Email Management System - Complete Guide

## ✅ System Implemented

A complete email management system has been integrated into the admin panel, allowing you to:
- ✅ Configure multiple email accounts (IMAP/POP3)
- ✅ Read emails within the system
- ✅ Reply to emails directly from the admin panel
- ✅ Manage email accounts in settings
- ✅ View email attachments
- ✅ Mark emails as read/unread/important/starred
- ✅ Assign emails to team members

---

## 🎯 Features

### Email Account Management
- **Multiple Accounts**: Configure multiple email accounts
- **IMAP/POP3 Support**: Connect via IMAP or POP3 protocols
- **SMTP Configuration**: Send emails through configured SMTP servers
- **Connection Testing**: Test email account connections before saving
- **Default Account**: Set a default email account
- **Auto-fetch**: Configure automatic email fetching intervals

### Email Reading & Management
- **Inbox View**: View all emails in a clean, organized inbox
- **Email Details**: View full email with headers, body, and attachments
- **Status Management**: Mark as read/unread/archived/deleted/spam
- **Starring**: Mark important emails with stars
- **Search & Filter**: Search emails by subject, sender, or content
- **Account Filtering**: Filter emails by account

### Email Sending
- **Compose**: Create and send new emails
- **Reply**: Reply to existing emails with original message quoted
- **CC/BCC**: Support for carbon copy and blind carbon copy
- **Multiple Accounts**: Send from any configured email account

---

## 📋 Setup Instructions

### 1. Install PHP IMAP Extension

The system requires the PHP IMAP extension to fetch emails. Install it:

**Windows (XAMPP/Laragon):**
- Edit `php.ini`
- Uncomment: `extension=imap`
- Restart server

**Linux (Ubuntu/Debian):**
```bash
sudo apt-get install php-imap
sudo phpenmod imap
sudo systemctl restart apache2  # or nginx
```

**macOS:**
```bash
brew install php-imap
```

### 2. Configure Email Account

1. Go to **Admin Panel → Settings → Email Accounts**
2. Click **Add Email Account**
3. Fill in the details:
   - **Account Name**: Friendly name (e.g., "Support Email")
   - **Email Address**: Your email address
   - **Protocol**: IMAP (recommended) or POP3
   - **IMAP Settings**:
     - Host: `imap.gmail.com` (for Gmail)
     - Port: `993`
     - Encryption: `SSL`
   - **SMTP Settings**:
     - Host: `smtp.gmail.com` (for Gmail)
     - Port: `587`
     - Encryption: `TLS`
   - **Username**: Your email address
   - **Password**: Your email password (or app password for Gmail)
4. Click **Test Connection** to verify
5. Save the account

### 3. Fetch Emails

- **Manual Fetch**: Click "Fetch" button on any email account
- **Automatic Fetch**: Set up a cron job to fetch emails periodically

**Cron Job Example:**
```bash
# Add to crontab (crontab -e)
*/5 * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

Then add to `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->call(function () {
        $accounts = \App\Models\EmailAccount::where('is_active', true)->get();
        foreach ($accounts as $account) {
            \App\Services\EmailService::fetchEmails($account);
        }
    })->everyFiveMinutes();
}
```

---

## 🔧 Email Provider Settings

### Gmail
- **IMAP Host**: `imap.gmail.com`
- **IMAP Port**: `993`
- **IMAP Encryption**: `SSL`
- **SMTP Host**: `smtp.gmail.com`
- **SMTP Port**: `587`
- **SMTP Encryption**: `TLS`
- **Note**: Enable "Less secure app access" or use App Password

### Outlook/Hotmail
- **IMAP Host**: `outlook.office365.com`
- **IMAP Port**: `993`
- **IMAP Encryption**: `SSL`
- **SMTP Host**: `smtp.office365.com`
- **SMTP Port**: `587`
- **SMTP Encryption**: `TLS`

### Yahoo
- **IMAP Host**: `imap.mail.yahoo.com`
- **IMAP Port**: `993`
- **IMAP Encryption**: `SSL`
- **SMTP Host**: `smtp.mail.yahoo.com`
- **SMTP Port**: `587`
- **SMTP Encryption**: `TLS`

---

## 📁 File Structure

### Models
- `app/Models/EmailAccount.php` - Email account model
- `app/Models/EmailMessage.php` - Email message model
- `app/Models/EmailAttachment.php` - Email attachment model

### Controllers
- `app/Http/Controllers/Admin/EmailController.php` - Email management
- `app/Http/Controllers/Admin/EmailSettingsController.php` - Account settings

### Services
- `app/Services/EmailService.php` - Email fetching and sending logic

### Views
- `resources/views/admin/emails/index.blade.php` - Inbox
- `resources/views/admin/emails/show.blade.php` - View email
- `resources/views/admin/emails/compose.blade.php` - Compose email
- `resources/views/admin/settings/email-accounts.blade.php` - Account list
- `resources/views/admin/settings/email-account-form.blade.php` - Account form

### Migrations
- `database/migrations/2025_11_26_214152_create_email_accounts_table.php`
- `database/migrations/2025_11_26_214155_create_email_messages_table.php`
- `database/migrations/2025_11_26_214158_create_email_attachments_table.php`

---

## 🚀 Usage

### View Inbox
1. Go to **Admin Panel → Messages & Notifications → Email Management → Inbox**
2. View all emails
3. Click on any email to view details

### Compose Email
1. Go to **Inbox** → Click **Compose**
2. Select email account
3. Enter recipient, subject, and message
4. Click **Send Email**

### Reply to Email
1. Open an email
2. Click **Reply** button
3. Original message is automatically quoted
4. Type your reply and send

### Configure Email Account
1. Go to **Admin Panel → Settings → System Settings → Email Accounts**
2. Click **Add Email Account**
3. Fill in connection details
4. Test connection
5. Save

### Fetch Emails
- **Manual**: Click "Fetch" on account settings page
- **Automatic**: Set up cron job (see Setup Instructions)

---

## 🔐 Security Notes

1. **Password Encryption**: Email passwords are encrypted using Laravel's Crypt
2. **Access Control**: Only authorized roles can access email management
3. **Connection Security**: Always use SSL/TLS encryption
4. **App Passwords**: For Gmail, use App Passwords instead of regular passwords

---

## ⚠️ Important Notes

1. **IMAP Extension Required**: The system requires PHP IMAP extension to fetch emails
2. **Gmail App Passwords**: Gmail requires App Passwords if 2FA is enabled
3. **Rate Limits**: Email providers may have rate limits on fetching
4. **Large Attachments**: Large attachments may take time to download
5. **POP3 Limitation**: POP3 doesn't support folder management like IMAP

---

## 🐛 Troubleshooting

### "IMAP extension is not installed"
- Install PHP IMAP extension (see Setup Instructions)
- Restart web server

### "Failed to connect to IMAP server"
- Check host, port, and encryption settings
- Verify username and password
- Check firewall settings
- For Gmail, enable "Less secure app access" or use App Password

### "Connection test failed"
- Verify all settings are correct
- Check if email provider allows IMAP/POP3 access
- Some providers require enabling IMAP in account settings

### Emails not fetching
- Check account is active
- Verify connection settings
- Check server logs for errors
- Ensure cron job is running (if using automatic fetch)

---

## ✨ System Ready!

The email management system is fully integrated and ready to use. Configure your email accounts in settings and start managing emails within the admin panel!




