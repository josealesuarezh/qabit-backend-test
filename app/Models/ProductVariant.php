<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Products
{
    use HasFactory;

    public $table = 'product_variants';

    public $fillable = [
        'attributes',
        'price',
        'stock'
    ];


    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'attributes' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|integer'
    ];

    public function product(){
        return $this->belongsTo(Products::class,'product_id');
    }
}
