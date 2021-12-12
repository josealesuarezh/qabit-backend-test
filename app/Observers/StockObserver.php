<?php

namespace App\Observers;

use App\Jobs\NotifySubscribedClients;
use App\Models\Products;

class StockObserver
{

    public function updated(Products $products)
    {
        if ($products->isDirty('stock')){
            if ($products->getOriginal('stock') === 0 && (integer)$products->stock > 0){
                NotifySubscribedClients::dispatch($products);
            }
        }
    }

}
