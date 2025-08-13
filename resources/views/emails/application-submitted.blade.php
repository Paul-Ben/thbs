<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Application Submitted</title>
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
        .application-details {
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
        <h1>Application Submitted Successfully!</h1>
        <p>Your application has been received and is under review</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $application->applicant_surname }} {{ $application->applicant_othernames }},</p>
        
        <p>Thank you for submitting your application to THBS. We have successfully received your application and it is now under review.</p>
        
        <div class="application-details">
            <h3>Application Details:</h3>
            <p><strong>Application Number:</strong> {{ $application->application_number }}</p>
            <p><strong>Programme:</strong> {{ $application->programme->name ?? 'To be assigned' }}</p>
            <p><strong>Submission Date:</strong> {{ $application->created_at->format('F j, Y g:i A') }}</p>
            <p><strong>Status:</strong> <span style="color: #f96332;">Under Review</span></p>
        </div>
        
        <p>What happens next?</p>
        <ul>
            <li>Our admission team will review your application</li>
            <li>You will receive updates on your application status</li>
            <li>If approved, you'll receive further instructions</li>
        </ul>
        
        <p>You can retrieve your application using the above Application Number.</p>
        
        <a href="{{ route('application.printout', $application) }}" class="btn">View Application</a>
        
        <p>If you have any questions or need to make changes to your application, please contact our admission office.</p>
        
        <p>Thank you for choosing THBS!</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>&copy; {{ date('Y') }} THBS. All rights reserved.</p>
    </div>
</body>
</html> 