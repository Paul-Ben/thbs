<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use App\Mail\PaymentSuccessful;
use App\Mail\AptitudeTestPaymentSuccessful;
use App\Mail\SchoolFeePaymentSuccessful;
use App\Mail\ApplicationSubmitted;
use App\Models\ApplicationFeePayment;
use App\Models\AptitudeTestPayment;
use App\Models\SchoolFeePayment;
use App\Models\Application;

class NotificationService
{
    /**
     * Send payment successful notification
     */
    public function sendPaymentSuccessfulNotification(ApplicationFeePayment|AptitudeTestPayment|SchoolFeePayment $payment): void
    {
        if ($payment instanceof ApplicationFeePayment) {
            $payment->load('application');
            Mail::to($payment->application->email)
                ->send(new PaymentSuccessful($payment));
        } elseif ($payment instanceof AptitudeTestPayment) {
            $payment->load('application');
            Mail::to($payment->application->email)
                ->send(new AptitudeTestPaymentSuccessful($payment));
        } elseif ($payment instanceof SchoolFeePayment) {
            $payment->load(['student.user', 'schoolFee.schoolSession', 'schoolFee.semester']);
            Mail::to($payment->student->user->email)
                ->send(new SchoolFeePaymentSuccessful($payment));
        }
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