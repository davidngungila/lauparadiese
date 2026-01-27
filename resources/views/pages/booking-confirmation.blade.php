<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
</head>
<body style="font-family: 'Poppins', sans-serif; color: #333; line-height: 1.6; background-color: #f8f9fa; padding: 20px;">
    <div style="max-width: 600px; margin: 20px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff;">
        <h1 style="color: #1a4d3a; font-family: 'Century Gothic', sans-serif; text-align: center;">Your Adventure is Confirmed!</h1>
        <p>Hello {{ $booking->customer_name }},</p>
        <p>Thank you for booking with Adventure Tours! We're thrilled to have you join us. Here are the details of your upcoming journey:</p>

        <div style="border-top: 2px solid #3ea572; margin: 20px 0; padding-top: 20px;">
            <h2 style="color: #1a4d3a; margin-top: 0;">Booking Summary</h2>
            <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
            <p><strong>Tour:</strong> {{ $booking->tour->name }}</p>
            <p><strong>Departure Date:</strong> {{ $booking->departure_date->format('F j, Y') }}</p>
            <p><strong>Number of Travelers:</strong> {{ $booking->travelers }}</p>
            @if($booking->addons)
            <p><strong>Add-ons:</strong> {{ implode(', ', $booking->addons) }}</p>
            @endif
        </div>

        <div style="background-color: #e8f5f0; padding: 20px; border-radius: 8px; text-align: center; margin-top: 20px;">
            <span style="font-size: 1.1rem; color: #1a4d3a;">Total Price</span>
            <p style="font-size: 2rem; font-weight: 700; color: #3ea572; margin: 10px 0;">${{ number_format($booking->total_price, 2) }}</p>
        </div>

        <p style="margin-top: 30px;">We will send you a detailed pre-trip information packet within the next few days. If you have any questions in the meantime, please don't hesitate to contact us by replying to this email.</p>
        <p>Happy travels,</p>
        <p><strong>The Adventure Tours Team</strong></p>
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 0.8em; color: #777; text-align: center;">This is an automated confirmation. Adventure Tours, 123 Adventure St, Mountain View, CA 94041</p>
    </div>
</body>
</html>
