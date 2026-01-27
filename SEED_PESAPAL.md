# How to Seed Pesapal Payment Gateway

## Quick Setup

Run the seeder to automatically configure Pesapal with your credentials:

```bash
php artisan db:seed --class=PaymentGatewaySeeder
```

Or run all seeders:

```bash
php artisan db:seed
```

## What Gets Created

The seeder will create/update the Pesapal payment gateway with:

✅ **Gateway Name**: `pesapal`  
✅ **Display Name**: `Pesapal`  
✅ **Status**: Active  
✅ **Mode**: Live/Production (not test mode)  
✅ **Primary**: Yes (set as default payment gateway)  
✅ **Credentials**: Your live credentials pre-configured  
✅ **Supported Currencies**: KES, USD, TZS, UGX, RWF  
✅ **Supported Methods**: Card, Mobile Money, Bank Transfer  

## Credentials Configured

- **Live Consumer Key**: `qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD`
- **Live Consumer Secret**: `M89Yr4yZ/U6ImiNJNBbQyuNxRCU=`

## After Seeding

1. ✅ Pesapal gateway will be active and ready to use
2. ✅ It will be set as the primary payment gateway
3. ✅ You can start accepting payments immediately
4. ✅ Test at `/book-now`

## Update Existing Gateway

If Pesapal gateway already exists, the seeder will:
- Update it with the correct credentials
- Set it as primary
- Ensure it's active

## Environment Variables (Optional)

You can also set credentials via `.env` file:

```env
PESAPAL_LIVE_CONSUMER_KEY=qos3CiU3YjP0k5Jk2AWaCvE5RTW0OslD
PESAPAL_LIVE_CONSUMER_SECRET=M89Yr4yZ/U6ImiNJNBbQyuNxRCU=
```

If set, the seeder will use these values instead of hardcoded ones.

## Verify Setup

After seeding, check:

1. Go to Admin Panel → Settings → Payment Gateways
2. You should see "Pesapal" gateway
3. Status should be "Active"
4. Mode should be "Live"
5. Primary badge should be visible

---

**Ready to use!** 🚀









