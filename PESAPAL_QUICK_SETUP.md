# Pesapal Quick Setup Guide - LAU PARADISE ADVENTURES

## Your Pesapal Credentials

**⚠️ SECURITY WARNING**: Keep these credentials secure and never share them publicly!

- **Consumer Key**: `qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD`
- **Consumer Secret**: `M89Yr4yZ/U6ImiNJNBbQyuNxRCU=`

## Step-by-Step Setup Instructions

### Step 1: Access Admin Panel

1. Log in to your admin panel
2. Navigate to: **Settings → Payment Gateways**
   - URL: `/admin/settings/payment-gateways`

### Step 2: Create/Edit Pesapal Gateway

1. Click **"Add New Gateway"** or find existing Pesapal gateway
2. Fill in the following details:

#### Basic Information:
- **Gateway Name**: `pesapal` (must be exactly this)
- **Display Name**: `Pesapal`
- **Description**: `Secure online payment gateway for Tanzania`

#### Status Settings:
- **Is Active**: ✅ **Check this box** (Enable the gateway)
- **Is Test Mode**: ❌ **Uncheck this box** (You have live credentials)
- **Is Primary**: ✅ **Check this box** (Make it the default payment method)

#### Credentials (JSON Format):

Copy and paste this EXACT JSON into the credentials field:

```json
{
    "test_consumer_key": "",
    "test_consumer_secret": "",
    "live_consumer_key": "qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD",
    "live_consumer_secret": "M89Yr4yZ/U6ImiNJNBbQyuNxRCU="
}
```

#### Supported Currencies:
```json
["KES", "USD", "TZS"]
```

#### Supported Payment Methods:
```json
["card", "mobile_money", "bank_transfer"]
```

### Step 3: Save Configuration

1. Click **"Save"** or **"Update"** button
2. The gateway should now be active

### Step 4: Test the Integration

1. Go to your booking page: `/book-now`
2. Fill in a test booking
3. Submit the form
4. You should be redirected to Pesapal payment page
5. Complete a test payment (if available) or cancel to verify redirect works

### Step 5: Verify IPN Registration

The system will automatically register the IPN URL when processing the first payment. However, you can verify it's working:

1. Check your server logs for IPN callbacks
2. IPN URL should be: `https://yourdomain.com/pesapal/ipn`
3. You can also register it manually in Pesapal dashboard if needed

## Important URLs

### Your Callback URLs:
- **Callback URL**: `https://yourdomain.com/pesapal/callback`
- **IPN URL**: `https://yourdomain.com/pesapal/ipn`

### Pesapal API Endpoints (Live):
- **Authentication**: `https://pay.pesapal.com/v3/api/Auth/RequestToken`
- **Submit Order**: `https://pay.pesapal.com/v3/api/Transactions/SubmitOrderRequest`
- **Get Status**: `https://pay.pesapal.com/v3/api/Transactions/GetTransactionStatus`
- **Register IPN**: `https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN`

## Troubleshooting

### Issue: "Payment gateway not configured"
- **Solution**: Make sure the gateway name is exactly `pesapal` (lowercase)
- **Solution**: Verify "Is Active" is checked

### Issue: "Authentication failed"
- **Solution**: Double-check your Consumer Key and Secret are correct
- **Solution**: Ensure there are no extra spaces in the JSON
- **Solution**: Verify "Is Test Mode" is unchecked (you have live credentials)

### Issue: "Redirect not working"
- **Solution**: Check if Pesapal gateway is set as primary
- **Solution**: Verify the booking form is submitting correctly
- **Solution**: Check browser console for JavaScript errors

### Issue: "IPN not received"
- **Solution**: Ensure your server is accessible from the internet
- **Solution**: Check firewall settings allow incoming requests
- **Solution**: Verify IPN URL is registered in Pesapal

## Security Best Practices

1. ✅ **Never commit credentials to Git**
2. ✅ **Use HTTPS for all payment pages**
3. ✅ **Keep credentials in admin panel only**
4. ✅ **Regularly rotate credentials if compromised**
5. ✅ **Monitor payment transactions regularly**
6. ✅ **Set up email alerts for failed payments**

## Support

- **Pesapal Support**: support@pesapal.com
- **Pesapal Documentation**: https://developer.pesapal.com
- **Your Integration Status**: Live/Production

## Next Steps

1. ✅ Configure credentials in admin panel
2. ✅ Test booking flow
3. ✅ Verify email notifications work
4. ✅ Monitor first few transactions
5. ✅ Set up payment alerts

---

**Last Updated**: {{ date('Y-m-d') }}
**Status**: Ready for Production









