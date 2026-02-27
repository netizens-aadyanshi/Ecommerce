<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\OrderCancelledMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendOrderCancelledEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Order $order) {}

    public function handle(): void
    {
        Mail::to($this->order->user)->send(new OrderCancelledMail($this->order));
    }
}
