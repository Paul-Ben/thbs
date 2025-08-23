<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School Fee Payment Successful</title>
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
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 5px 5px;
        }
        .payment-details {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Successful!</h1>
        <p>Your school fee payment has been processed successfully</p>
    </div>
    
    <div class="content">
        <p>Dear {{ $payment->student->user->name }},</p>
        
        <p>We are pleased to confirm that your school fee payment has been successfully processed.</p>
        
        <div class="payment-details">
            <h3>Payment Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Fee Name:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $payment->schoolFee->name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Amount Paid:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><span class="amount">₦{{ number_format($payment->amount, 2) }}</span></td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Payment Method:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ ucfirst($payment->payment_method) }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Transaction ID:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $payment->transaction_id }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Payment Date:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $payment->payment_date->format('F j, Y g:i A') }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;"><strong>Session:</strong></td>
                    <td style="padding: 8px 0; border-bottom: 1px solid #eee;">{{ $payment->schoolFee->schoolSession->session_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0;"><strong>Semester:</strong></td>
                    <td style="padding: 8px 0;">{{ $payment->schoolFee->semester->semester_name ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
        
        <p>This payment has been recorded in your student account. You can view your payment history and download receipts from your student dashboard.</p>
        
        <p>If you have any questions about this payment or need assistance, please contact the Bursar's Office.</p>
        
        <p>Thank you for your prompt payment!</p>
        
        <p>Best regards,<br>
        <strong>The Hillside Business School</strong><br>
        Bursar's Office</p>
    </div>
    
    <div class="footer">
        <p><small>This is an automated email. Please do not reply to this message.</small></p>
    </div>
</body>
</html>