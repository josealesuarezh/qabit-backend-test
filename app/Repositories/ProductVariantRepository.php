<?php

namespace App\Repositories;

use App\Models\ProductVariant;
use App\Repositories\BaseRepository;

/**
 * Class ProductVariantRepository
 * @package App\Repositories
 * @version December 12, 2021, 4:53 am UTC
*/

class ProductVariantRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return ProductVariant::class;
    }
}
