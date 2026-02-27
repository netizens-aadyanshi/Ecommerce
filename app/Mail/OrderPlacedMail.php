<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        // Eager load items and products to ensure they are available in the view
        $this->order = $order->load('orderItems.product');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation - #' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.placed',
        );
    }
}
