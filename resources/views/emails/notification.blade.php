<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .message {
            background-color: white;
            padding: 20px;
            border-left: 4px solid #4CAF50;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Tour Booking System</h1>
    </div>
    <div class="content">
        <h2>{{ $subject ?? 'Notification' }}</h2>
        
        <div class="message">
            {!! nl2br(e($emailMessage)) !!}
        </div>

        @if(isset($data['booking']))
            <div style="background-color: white; padding: 15px; margin-top: 20px; border-radius: 5px;">
                <h3>Booking Details:</h3>
                <p><strong>Reference:</strong> {{ $data['booking']->booking_reference ?? 'N/A' }}</p>
                @if(isset($data['booking']->tour))
                    <p><strong>Tour:</strong> {{ $data['booking']->tour->name ?? 'N/A' }}</p>
                @endif
                <p><strong>Date:</strong> {{ $data['booking']->departure_date->format('F j, Y') ?? 'N/A' }}</p>
                <p><strong>Travelers:</strong> {{ $data['booking']->travelers ?? 'N/A' }}</p>
                <p><strong>Total:</strong> ${{ number_format($data['booking']->total_price ?? 0, 2) }}</p>
            </div>
        @endif
    </div>
    
    <div class="footer">
        <p>This is an automated message from Lau Paradise Adventures.</p>
        <p>For inquiries, contact us:</p>
        <p><strong>Email:</strong> <a href="mailto:lauparadiseadventure@gmail.com">lauparadiseadventure@gmail.com</a></p>
        <p><strong>Phone:</strong> <a href="tel:+255683163219">+255 683 163 219</a></p>
        <p style="margin-top: 15px; font-size: 11px; color: #999;">Please do not reply to this email. Use the contact information above for inquiries.</p>
    </div>
</body>
</html>






