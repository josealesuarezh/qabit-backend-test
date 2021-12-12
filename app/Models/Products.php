<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


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
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
    ];

    public function getTotalStockAttribute() {
        return $this->variants()->sum('stock');
    }

    /**
     * @return HasOne
     */
    public function lowestPriceVariation(): HasOne
    {
        return $this->hasOne(ProductVariant::class,'product_id')->orderBy('price');
    }

    public function latestUpdated()
    {
        return $this->hasOne(ProductVariant::class,'product_id')->latest();
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class,'product_id','id');
    }

}
