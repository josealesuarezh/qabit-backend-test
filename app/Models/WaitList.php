<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WaitList extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'email' => 'required|email',
        'product_id' => 'required|exists:products,id',
    ];
}
