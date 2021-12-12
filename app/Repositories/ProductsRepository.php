<?php

namespace App\Repositories;

use App\Models\Products;
use App\Repositories\BaseRepository;

/**
 * Class ProductsRepository
 * @package App\Repositories
 * @version December 10, 2021, 7:01 pm UTC
 */
class ProductsRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'description',
        'attributes',
        'price',
        'stock'
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
        return Products::class;
    }

    public function allQuery($search = [], $skip = null, $limit = null)
    {
        $query = $this->model->newQuery();

        if (count($search)) {
            if (isset($search['minPrice'])) {
                $query->where('price', '>=', $search['minPrice']);
            }

            if (isset($search['maxPrice'])) {
                $query->where('price', '<=', $search['maxPrice']);
            }

            if (isset($search['inStock'])) {
                if ($search['inStock'] === "true") {
                    $query->where('stock', '>', 0);
                } else if ($search['inStock'] === "false"){
                    $query->where('stock', '=', 0);
                }
            }

            if (isset($search['name'])) {
                $query->where('name', 'like', '%' . $search['name'] . '%');
            }
            if (isset($search['description'])) {
                $query->where('description', 'like', '%' . $search['description'] . '%');
            }

            $attributes = array_diff_key($search,array_flip($this->getFieldsSearchable()));
            foreach($attributes as $key => $value) {
                $query->where("attributes->".$key, $value);
            }
        }

        if (!is_null($skip)) {
            $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query->limit($limit);
        }

        return $query;
    }
}
