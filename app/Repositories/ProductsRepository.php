<?php

namespace App\Repositories;

use App\Models\Products;
use App\Repositories\BaseRepository;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
        'description'
    ];

    protected $customSearchableAttributes = [
        'minPrice',
        'maxPrice',
        'inStock'
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

        if (empty($search)) {
            return $query->with('lowestPriceVariation');
        }


        $query = $query->with('variants');

        if (count($search)) {

            if (isset($search['name'])) {
                $query->where('name', 'like', '%' . $search['name'] . '%');
            }
            if (isset($search['description'])) {
                $query->where('description', 'like', '%' . $search['description'] . '%');
            }


            $query->withWhereHas('variants', function ($query) use ($search) {

                if (isset($search['minPrice']))
                    $query->where('price', '>=', $search['minPrice']);

                if (isset($search['maxPrice']))
                    $query->where('price', '<=', $search['maxPrice']);

                if (isset($search['inStock'])) {
                    if ($search['inStock'] === "true") {
                        $query->where('stock', '>', 0);
                    } else {
                        if ($search['inStock'] === "false") {
                            $query->where('stock', '=', 0);
                        }
                    }
                }

                $definedAttributes = array_merge($this->customSearchableAttributes, $this->fieldSearchable);
                $remaining = array_diff_key($search, array_flip($definedAttributes));
                foreach ($remaining as $key => $value) {
                    $query->where("attributes->" . $key, $value);
                }
            });

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
