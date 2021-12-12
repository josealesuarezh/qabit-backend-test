<?php

namespace App\Http\Controllers\API;


use App\Models\Products;
use App\Models\ProductVariant;
use App\Repositories\ProductVariantRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\ProductVariantResource;
use Illuminate\Support\Facades\Response;

/**
 * Class ProductVariantController
 * @package App\Http\Controllers\API
 */

class ProductVariantAPIController extends AppBaseController
{

    private $productVariantRepository;

    public function __construct(ProductVariantRepository $productVariantRepo)
    {
        $this->productVariantRepository = $productVariantRepo;
    }

    /**
     * Store a newly created ProductVariant in storage.
     * POST /productVariants
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Products $products, Request $request)
    {
        $input = $request->validate(ProductVariant::$rules);

        $productVariant = $products->variants()->create($input);
        $productVariant->load('product');
        return $this->sendResponse(new ProductVariantResource($productVariant), 'Product Variant saved successfully');
    }


    /**
     * Remove the specified ProductVariant from storage.
     * DELETE /productVariants/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ProductVariant $productVariant */
        $productVariant = $this->productVariantRepository->find($id);

        if (empty($productVariant)) {
            return $this->sendError('Product Variant not found');
        }

        $productVariant->delete();

        return $this->sendSuccess('Product Variant deleted successfully');
    }
}
