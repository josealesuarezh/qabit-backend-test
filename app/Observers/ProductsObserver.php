<?php

namespace App\Observers;

use App\Jobs\NotifySubscribedClients;
use App\Models\Products;

class ProductsObserver
{

    public function updated(Products $products)
    {
        NotifySubscribedClients::dispatch($products);
//        if ($products->isDirty('stock')){
//            if ($products->getOriginal('stock') === 0 && $products->stock > 0){
//                NotifySubscribedClients::dispatch($products);
//            }
//        }
    }

}
