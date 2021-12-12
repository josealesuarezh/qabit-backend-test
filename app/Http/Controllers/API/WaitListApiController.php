<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Products;
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
            WaitList::create($attributes);
            return $this->sendSuccess('You have been subscribed successfully');
        }
    }
}
