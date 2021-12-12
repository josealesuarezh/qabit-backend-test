<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Jobs\NotifySubscribedClients;
use App\Models\Products;
use App\Models\ProductVariant;
use App\Models\WaitList;
use Illuminate\Http\Request;


class WaitListApiController extends AppBaseController
{
    public function toggleSubscribe(Request $request){
        $attributes = $request->validate(WaitList::$rules);

        if($subscription = WaitList::where($attributes)->first()){
            $subscription->delete();
            return $this->sendSuccess('You have been unsubscribed successfully');
        }else{
            $productVariation = ProductVariant::where('product_id',$attributes['product_id'])->firstOrFail();
            if ($productVariation->stock > 0)
            {
                return $this->sendError('You cannot subscribe to a product that is already on existence',403);
            }
            WaitList::create($attributes);
            return $this->sendSuccess('You have been subscribed successfully');
        }
    }
}
