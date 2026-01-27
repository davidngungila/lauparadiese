# Pesapal API 3.0 Configuration Guide

This guide will help you configure Pesapal payment gateway for your booking system.

## Prerequisites

1. **Pesapal Merchant Account** ✅
   - Account: LAU PARADISE ADVENTURES
   - Status: Active (Live credentials received)

2. **API Credentials** ✅
   - **Consumer Key**: `qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD`
   - **Consumer Secret**: `M89Yr4yZ/U6ImiNJNBbQyuNxRCU=`
   - **Environment**: Live/Production
   - ⚠️ **SECURITY**: Keep these credentials secure!

## Configuration Steps

### Step 1: Access Payment Gateway Settings

1. Log in to your admin panel
2. Navigate to: **Settings → Payment Gateways**
3. Find or create the "Pesapal" payment gateway

### Step 2: Configure Pesapal Gateway

#### For Sandbox/Testing Environment:

1. **Gateway Name**: `pesapal`
2. **Display Name**: `Pesapal`
3. **Is Active**: ✅ Check this box
4. **Is Test Mode**: ✅ Check this box (for testing)
5. **Credentials** (JSON format):
```json
{
    "test_consumer_key": "YOUR_SANDBOX_CONSUMER_KEY",
    "test_consumer_secret": "YOUR_SANDBOX_CONSUMER_SECRET",
    "live_consumer_key": "",
    "live_consumer_secret": ""
}
```

#### For Production/Live Environment (YOUR CURRENT SETUP):

1. **Is Test Mode**: ❌ **Uncheck this box** (You have live credentials)
2. **Credentials** (JSON format) - **USE THESE EXACT VALUES**:
```json
{
    "test_consumer_key": "",
    "test_consumer_secret": "",
    "live_consumer_key": "qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD",
    "live_consumer_secret": "M89Yr4yZ/U6ImiNJNBbQyuNxRCU="
}
```

**⚠️ IMPORTANT**: 
- These are your LIVE production credentials
- Never share these publicly
- Keep them secure in the admin panel only

### Step 3: Register IPN URL

The system will automatically register the IPN URL when processing the first payment. However, you can also register it manually:

**IPN URL**: `https://yourdomain.com/pesapal/ipn`

**IPN Notification Type**: `GET` (recommended) or `POST`

You can register IPN URLs using:
- **Sandbox IPN Registration**: https://cybqa.pesapal.com/pesapalv3/api/URLSetup/RegisterIPN
- **Live IPN Registration**: https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN

Or use the online forms:
- **Sandbox Form**: https://cybqa.pesapal.com/pesapalv3/api/URLSetup/RegisterIPN
- **Live Form**: https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN

### Step 4: Test Credentials (Sandbox)

For testing purposes, you can use these sandbox credentials:

```
Consumer Key: qkio1BGGYAXTu2JOfm7XSXNruoZsrqEW
Consumer Secret: (Contact Pesapal support for sandbox secret)
```

**Note**: These are example credentials. Use your actual sandbox credentials provided by Pesapal.

### Step 5: Verify Configuration

1. **Test Payment Flow**:
   - Go to `/book-now`
   - Fill in booking details
   - Submit the form
   - You should be redirected to Pesapal payment page

2. **Check IPN Endpoint**:
   - Ensure `https://yourdomain.com/pesapal/ipn` is accessible
   - Check server logs for IPN callbacks

3. **Verify Callback**:
   - After payment, check if callback is received at `/pesapal/callback`
   - Verify booking status is updated correctly

## API Endpoints Used

### 1. Authentication
- **Sandbox**: `https://cybqa.pesapal.com/pesapalv3/api/Auth/RequestToken`
- **Live**: `https://pay.pesapal.com/v3/api/Auth/RequestToken`

### 2. Register IPN
- **Sandbox**: `https://cybqa.pesapal.com/pesapalv3/api/URLSetup/RegisterIPN`
- **Live**: `https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN`

### 3. Submit Order Request
- **Sandbox**: `https://cybqa.pesapal.com/pesapalv3/api/Transactions/SubmitOrderRequest`
- **Live**: `https://pay.pesapal.com/v3/api/Transactions/SubmitOrderRequest`

### 4. Get Transaction Status
- **Sandbox**: `https://cybqa.pesapal.com/pesapalv3/api/Transactions/GetTransactionStatus`
- **Live**: `https://pay.pesapal.com/v3/api/Transactions/GetTransactionStatus`

## Callback URLs

### Callback URL (Customer Redirect)
- **URL**: `https://yourdomain.com/pesapal/callback`
- **Purpose**: Redirects customer after payment
- **Method**: GET

### IPN URL (Server Notification)
- **URL**: `https://yourdomain.com/pesapal/ipn`
- **Purpose**: Server-to-server payment notifications
- **Method**: GET (or POST if configured)

## Payment Flow

1. **Customer submits booking form** → `/book-now`
2. **System creates booking** → Status: `pending_payment`
3. **System calls Pesapal API** → Submit Order Request
4. **Customer redirected** → Pesapal payment page
5. **Customer completes payment** → On Pesapal
6. **Pesapal redirects back** → `/pesapal/callback?OrderTrackingId=...`
7. **System verifies payment** → Get Transaction Status
8. **System updates booking** → Status: `confirmed`
9. **System sends email** → Booking confirmation

## Troubleshooting

### Issue: Payment not redirecting to Pesapal

**Solutions**:
- Check if Pesapal gateway is active in admin panel
- Verify consumer key and secret are correct
- Check server logs for API errors
- Ensure IPN URL is registered

### Issue: IPN not received

**Solutions**:
- Verify IPN URL is publicly accessible
- Check if IPN URL is registered in Pesapal dashboard
- Review server firewall/security settings
- Check server logs for incoming requests

### Issue: Payment status not updating

**Solutions**:
- Verify callback URL is accessible
- Check transaction status API response
- Review booking update logic in `PesapalController`
- Check database for payment records

### Issue: Authentication errors

**Solutions**:
- Verify consumer key and secret
- Check token expiration (tokens expire after 5 minutes)
- Ensure correct environment (sandbox vs live)
- Review API endpoint URLs

## Security Considerations

1. **Never commit credentials to version control**
2. **Use environment variables for sensitive data**
3. **Enable HTTPS for all payment-related endpoints**
4. **Validate all IPN callbacks**
5. **Log all payment transactions**
6. **Implement rate limiting on payment endpoints**

## Support

- **Pesapal Documentation**: https://developer.pesapal.com
- **Pesapal Support**: support@pesapal.com
- **Sandbox Test Credentials**: Contact Pesapal support

## Testing Checklist

- [ ] Pesapal gateway configured in admin panel
- [ ] Consumer key and secret added
- [ ] Test mode enabled for sandbox testing
- [ ] IPN URL registered
- [ ] Callback URL accessible
- [ ] Test payment completed successfully
- [ ] Booking status updated after payment
- [ ] Confirmation email sent
- [ ] IPN callback received and processed
- [ ] Production credentials configured (when ready)
- [ ] Test mode disabled for production

## Production Checklist

- [ ] Live consumer key and secret configured
- [ ] Test mode disabled
- [ ] IPN URL registered in live environment
- [ ] SSL certificate installed (HTTPS required)
- [ ] All payment endpoints tested
- [ ] Email notifications working
- [ ] Error logging configured
- [ ] Backup and recovery plan in place

