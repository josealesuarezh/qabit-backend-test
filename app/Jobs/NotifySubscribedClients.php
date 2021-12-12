<?php

namespace App\Jobs;

use App\Mail\ProductAvailable;
use App\Models\Products;
use App\Models\WaitList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifySubscribedClients implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $product;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Products $products)
    {
        $this->product = $products;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $available = $this->product->stock;
        $waitList = WaitList::where($this->product->id,'product_id')->get();
        while ($available >= 1 && $waitList->isNotEmpty()){
            $email = $waitList->shift()->email;
            Mail::to($email)->send(new ProductAvailable($this->product));
            $available--;
        }
    }
}
