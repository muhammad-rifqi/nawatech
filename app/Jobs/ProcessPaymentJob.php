<?php

namespace App\Jobs;

use App\Http\Services\OrderService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessPaymentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $orderId;

    public function __construct($orderId)
    {
         $this->orderId = $orderId;
    }

    /**
     * Execute the job.
     */
    public function handle(OrderService $orderService): void
    {
        //
        sleep(2);
        $status = rand(0,1) ? 'completed' : 'failed';
        $orderService->updatePaymentStatus($this->orderId, $status);
    }
}
