<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Your Subscription</title>
</head>
<body style="font-family: 'Poppins', sans-serif; color: #333; line-height: 1.6;">
    <div style="max-width: 600px; margin: 20px auto; padding: 30px; border-radius: 8px; background-color: #fff;">
        <h1 style="color: #1a4d3a;">One Last Step!</h1>
        <p>Thanks for your interest in the Adventure Tours newsletter. Please click the button below to confirm your email address and complete your subscription.</p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/newsletter/verify/' . $subscriber->verification_token) }}" style="background-color: #3ea572; color: #fff; padding: 15px 30px; text-decoration: none; border-radius: 50px; font-weight: 600;">Confirm Subscription</a>
        </div>
        <p>If you did not request this subscription, you can safely ignore this email.</p>
        <p>Happy travels,<br>The Adventure Tours Team</p>
    </div>
</body>
</html>
