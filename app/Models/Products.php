<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Model;



/**
 * Class Products
 * @package App\Models
 * @version December 10, 2021, 7:00 pm UTC
 *
 * @property string $name
 * @property string $description
 * @property string $attributes
 * @property number $price
 * @property integer $stock
 */
class Products extends Model
{

    use HasFactory;

    public $table = 'products';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public $fillable = [
        'name',
        'description',
        'attributes',
        'price',
        'stock'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'description' => 'string',
        'attributes' => 'array',
        'price' => 'float',
        'stock' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'attributes' => 'required|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer'
    ];


}
