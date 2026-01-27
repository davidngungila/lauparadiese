<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Newsletter Subscriber</title>
</head>
<body style="font-family: 'Poppins', sans-serif; color: #333; line-height: 1.6; background-color: #f8f9fa; padding: 20px;">
    <div style="max-width: 600px; margin: 20px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; background-color: #fff;">
        <h1 style="color: #1a4d3a; font-family: 'Century Gothic', sans-serif; text-align: center;">
            <i class="fas fa-envelope-open-text" style="color: #3ea572;"></i> New Subscriber Alert!
        </h1>
        <p>A new user has subscribed to the Lau Paradise Adventures newsletter through the website.</p>

        <div style="border-top: 2px solid #3ea572; margin: 20px 0; padding-top: 20px;">
            <h2 style="color: #1a4d3a; margin-top: 0;">Subscriber Details</h2>
            <p><strong>Email Address:</strong> <a href="mailto:{{ $subscriber->email }}" style="color: #3ea572; text-decoration: none;">{{ $subscriber->email }}</a></p>
            <p><strong>Subscribed On:</strong> {{ $subscriber->created_at->format('F j, Y \a\t g:i A') }}</p>
        </div>

        <p style="margin-top: 30px; text-align: center;">
            You can view all subscribers in the admin dashboard.
        </p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="font-size: 0.8em; color: #777; text-align: center;">
            This is an automated notification from Lau Paradise Adventures.<br>
            Contact: <a href="mailto:lauparadiseadventure@gmail.com" style="color: #3ea572;">lauparadiseadventure@gmail.com</a> | 
            <a href="tel:+255683163219" style="color: #3ea572;">+255 683 163 219</a>
        </p>
    </div>
</body>
</html>

