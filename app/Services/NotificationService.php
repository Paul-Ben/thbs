<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use App\Mail\PaymentSuccessful;
use App\Mail\ApplicationSubmitted;
use App\Models\Payment;
use App\Models\Application;

class NotificationService
{
    /**
     * Send payment successful notification
     */
    public function sendPaymentSuccessfulNotification(Payment $payment): void
    {
        $payment->load('application');

        Mail::to($payment->application->email)
            ->send(new PaymentSuccessful($payment));
    }

    /**
     * Send application submitted notification
     */
    public function sendApplicationSubmittedNotification(Application $application): void
    {
        Mail::to($application->email)
            ->send(new ApplicationSubmitted($application));
    }

    /**
     * Send custom email notification
     */
    public function sendCustomEmail(string $to, Mailable $mailable): void
    {
        Mail::to($to)->send($mailable);
    }

    /**
     * Send bulk email notifications
     */
    public function sendBulkEmail(array $recipients, Mailable $mailable): void
    {
        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send($mailable);
        }
    }
}