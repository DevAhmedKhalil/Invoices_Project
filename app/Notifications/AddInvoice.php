<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invoice;

class AddInvoice extends Notification
{
    use Queueable;

    protected Invoice $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/invoices-details/' . $this->invoice->id);

        return (new MailMessage)
            ->greeting('مرحباً!')
            ->line('تم إضافة فاتورة جديدة.')
            ->line('رقم الفاتورة: ' . $this->invoice->invoice_number)
            ->action('عرض الفاتورة', $url)
            ->line('شكراً لاستخدامك نظام إدارة الفواتير.');
    }
}
