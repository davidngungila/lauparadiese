<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f8f9fa;
      font-family: Arial, sans-serif;
    }
    .email-container {
      max-width: 600px;
      margin: 30px auto;
      border: 1px solid #dee2e6;
      background-color: #ffffff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      font-size: 17px;
    }
    .header {
      background: linear-gradient(135deg, #3ea572 0%, #2d7a5f 100%);
      color: #ffffff;
      text-align: center;
      padding: 25px 20px;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
    }
    .header-logo {
      max-width: 120px;
      height: auto;
      margin-bottom: 15px;
    }
    .title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 8px;
    }
    .sub-title {
      font-size: 14px;
      line-height: 1.6;
      opacity: 0.95;
    }
    .body {
      padding: 30px;
      color: #343a40;
      line-height: 1.7;
    }
    .body p {
      margin-bottom: 18px;
    }
    .greeting {
      font-size: 18px;
      margin-bottom: 20px;
    }
    .highlight-green {
      font-weight: bold;
      color: #3ea572;
    }
    .highlight-blue {
      font-weight: bold;
      color: #2d7a5f;
    }
    .highlight-orange {
      font-weight: bold;
      color: #6cbe8f;
    }
    .highlight-red {
      font-weight: bold;
      color: #1a4d3a;
    }
    .info-box {
      background-color: #e6f4ed;
      border-left: 4px solid #3ea572;
      padding: 15px;
      margin: 20px 0;
      border-radius: 5px;
    }
    .warning-box {
      background-color: #fff3cd;
      border-left: 4px solid #6cbe8f;
      padding: 15px;
      margin: 20px 0;
      border-radius: 5px;
    }
    .quote {
      font-style: italic;
      margin: 20px 0;
      border-left: 4px solid #2d7a5f;
      padding-left: 15px;
      color: #6c757d;
    }
    .button-container {
      text-align: center;
      margin: 30px 0;
    }
    .cta-button {
      display: inline-block;
      background-color: #3ea572;
      color: #ffffff !important;
      padding: 14px 28px;
      font-size: 16px;
      text-align: center;
      text-decoration: none;
      border-radius: 6px;
      margin: 8px;
      font-weight: bold;
      transition: background-color 0.3s;
    }
    .cta-button:hover {
      background-color: #2d7a5f;
    }
    .cta-button-secondary {
      background-color: #6cbe8f;
    }
    .cta-button-secondary:hover {
      background-color: #3ea572;
    }
    .cta-button-success {
      background-color: #2d7a5f;
    }
    .cta-button-success:hover {
      background-color: #1a4d3a;
    }
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }
    .details-table td {
      padding: 10px;
      border-bottom: 1px solid #dee2e6;
    }
    .details-table td:first-child {
      font-weight: bold;
      color: #6c757d;
      width: 40%;
    }
    .footer {
      background-color: #2d7a5f;
      color: #ffffff;
      text-align: center;
      padding: 20px;
      font-size: 14px;
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }
    .footer-links {
      margin-top: 10px;
    }
    .footer-links a {
      color: #ffffff;
      text-decoration: none;
      margin: 0 10px;
    }
    .attachment-notice {
      background-color: #e6f4ed;
      border: 1px solid #3ea572;
      padding: 12px;
      border-radius: 5px;
      margin: 20px 0;
      font-size: 14px;
    }
    .attachment-notice strong {
      color: #2d7a5f;
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="header">
      @if(isset($logo) && $logo)
      <img src="{{ $logo }}" alt="Company Logo" class="header-logo">
      @endif
      <div class="title">{{ $companyName ?? 'Lau Paradise Adventures' }}</div>
      <div class="sub-title">
        {{ $companyAddress ?? 'P.O.Box, Arusha, Tanzania' }}<br>
        @if(isset($companyPhone))
        Phone: {{ $companyPhone }} | 
        @endif
        @if(isset($companyEmail))
        Email: {{ $companyEmail }}
        @endif
      </div>
    </div>
    
    <div class="body">
      <div class="greeting">
        <p>Dear <b class="highlight-green">{{ $recipientName ?? 'Valued Customer' }}</b>,</p>
      </div>
      
      {!! $emailContent !!}
      
      @if(isset($attachmentName))
      <div class="attachment-notice">
        <strong>📎 Attachment:</strong> {{ $attachmentName }} is attached to this email.
      </div>
      @endif
      
      @if(isset($buttons) && count($buttons) > 0)
      <div class="button-container">
        @foreach($buttons as $button)
        <a href="{{ $button['url'] }}" class="cta-button {{ $button['class'] ?? '' }}" style="color: #ffffff !important;">
          {{ $button['text'] }}
        </a>
        @endforeach
      </div>
      @endif
      
      @if(isset($quote))
      <div class="quote">
        "{{ $quote }}"
      </div>
      @endif
      
      <p style="margin-top: 30px;">
        Thank you for choosing <span class="highlight-red">Lau Paradise Adventures</span>!<br>
        We look forward to providing you with an unforgettable experience.
      </p>
      
      <p>
        Best regards,<br>
        <strong>{{ $companyName ?? 'Lau Paradise Adventures' }} Team</strong>
      </p>
    </div>
    
    <div class="footer">
      <div>{{ $companyName ?? 'Lau Paradise Adventures' }} - {{ $documentType ?? 'Document' }} System</div>
      <div style="margin-top: 8px; font-size: 12px; opacity: 0.9;">
        @if(isset($companyWebsite))
        <a href="{{ $companyWebsite }}" target="_blank">Visit Our Website</a> |
        @endif
        @if(isset($companyEmail))
        <a href="mailto:{{ $companyEmail }}">Contact Us</a>
        @endif
      </div>
      <div style="margin-top: 10px; font-size: 11px; opacity: 0.8;">
        &copy; {{ date('Y') }} {{ $companyName ?? 'Lau Paradise Adventures' }}. All rights reserved.
      </div>
    </div>
  </div>
</body>
</html>

