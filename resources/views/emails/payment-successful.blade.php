<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #1a2035;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1a2035 0%, #f96332 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }
        .payment-details {
            background-color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #f96332;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #1a2035 0%, #f96332 100%);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            margin: 10px 0;
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #1a2035;
            font-size: 12px;
        }
        .bsth-header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            border-bottom: 2px solid #f96332;
        }
        .bsth-logo {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }
        .bsth-acronym {
            margin: 0;
            color: #f96332;
            font-size: 24px;
            font-weight: bold;
        }
        .bsth-name {
            margin: 5px 0;
            color: #1a2035;
            font-size: 18px;
            font-weight: bold;
        }
        .bsth-institute {
            margin: 5px 0;
            color: #666;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="bsth-header">
        <img src="{{ asset('assets/img/bsth-logo.jpeg') }}" alt="BSTH Logo" class="bsth-logo">
        <h3 class="bsth-name">BENUE STATE UNIVERSITY TEACHING HOSPITAL MAKURDI</h3>
        <h4 class="bsth-institute">INSTITUTE OF HEALTH AND TECHNOLOGY</h4>
    </div>
    
    <div class="header">
        <h1>Payment Successful!</h1>
        <p>Your application fee has been processed successfully</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $payment->application->applicant_surname }} {{ $payment->application->applicant_othernames }},</p>
        
        <p>We are pleased to confirm that your application fee payment has been processed successfully.</p>
        
        <div class="payment-details">
            <h3>Payment Details:</h3>
            <p><strong>Reference:</strong> {{ $payment->reference }}</p>
            <p><strong>Amount:</strong> ₦{{ number_format($payment->amount, 2) }}</p>
            <p><strong>Date:</strong> {{ $payment->payment_date->format('F j, Y g:i A') }}</p>
            <p><strong>Status:</strong> <span style="color: #f96332;">Successful</span></p>
        </div>
        
        <p>You can now proceed with your application by clicking the button below:</p>
        
        <a href="{{ route('application.create', $payment->reference) }}" class="btn">Continue Application</a>
        
        <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>
        
        <p>Thank you for choosing THBS!</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} THBS. All rights reserved.</p>
    </div>
</body>
</html> 