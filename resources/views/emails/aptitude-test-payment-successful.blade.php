<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aptitude Test Payment Successful</title>
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
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
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
            border-left: 4px solid #007bff;
        }
        .test-info {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
            border-left: 4px solid #2196f3;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
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
            border-bottom: 2px solid #007bff;
        }
        .bsth-logo {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }
        .bsth-acronym {
            margin: 0;
            color: #007bff;
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
        .graduation-icon {
            font-size: 48px;
            color: #007bff;
            margin-bottom: 10px;
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
        <div class="graduation-icon">🎓</div>
        <h1>Aptitude Test Payment Successful!</h1>
        <p>Your aptitude test fee has been processed successfully</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $payment->application->applicant_surname }} {{ $payment->application->applicant_othernames }},</p>
        
        <p>We are pleased to confirm that your aptitude test fee payment has been processed successfully. You are now eligible to take the aptitude test for your chosen programme.</p>
        
        <div class="payment-details">
            <h3>Payment Details:</h3>
            <p><strong>Reference:</strong> {{ $payment->reference }}</p>
            <p><strong>Amount:</strong> ₦{{ number_format($payment->amount, 2) }}</p>
            <p><strong>Date:</strong> {{ $payment->payment_date->format('F j, Y g:i A') }}</p>
            <p><strong>Status:</strong> <span style="color: #007bff;">Successful</span></p>
            <p><strong>Application Number:</strong> {{ $payment->application->application_number }}</p>
        </div>

        <div class="test-info">
            <h3>📋 Next Steps:</h3>
            <ul>
                <li><strong>Test Schedule:</strong> You will receive a separate email with your test date, time, and venue</li>
                <li><strong>Required Items:</strong> Bring a valid ID, writing materials, and this payment confirmation</li>
                <li><strong>Test Duration:</strong> The aptitude test typically lasts 2 hours</li>
                <li><strong>Results:</strong> Test results will be communicated within 5 working days</li>
            </ul>
        </div>
        
        <p><strong>Important:</strong> Please keep this email as proof of payment. You may be required to present it on the test day.</p>
        
        <p>If you have any questions about the aptitude test or need assistance, please contact our admissions office.</p>
        
        <p>Good luck with your aptitude test!</p>
        
        <p>Best regards,<br>
        <strong>THBS Admissions Team</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} THBS. All rights reserved.</p>
    </div>
</body>
</html>
