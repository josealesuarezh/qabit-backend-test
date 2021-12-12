<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Models\Products;
use App\Models\ProductVariant;
use App\Repositories\ProductsRepository;
use App\Repositories\ProductVariantRepository;
use Illuminate\Http\Request;
use App\Http\Resources\ProductsResource;
use Illuminate\Support\Facades\Response;

/**
 * Class ProductsController
 * @package App\Http\Controllers\API
 */
class ProductsAPIController extends AppBaseController
{
    /** @var  ProductsRepository */
    private $productsRepository;
    private $productVariantRepository;

    public function __construct(ProductsRepository $productsRepo, ProductVariantRepository $productVariantRepo)
    {
        $this->productsRepository = $productsRepo;
        $this->productVariantRepository = $productVariantRepo;

    }

    /**
     * Display a listing of the Products.
     * GET|HEAD /products
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $products = $this->productsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(ProductsResource::collection($products), 'Products retrieved successfully');
    }

    /**
     * Store a newly created Products in storage.
     * POST /products
     *
     * @param Request $request
     *
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $product = $request->validate(Products::$rules);
        $variant = $request->validate(ProductVariant::$rules);

        $products = $this->productsRepository->create($product);

        $products->variants()->create($variant);

        $products->load('variants');

        return $this->sendResponse(new ProductsResource($products), 'Products saved successfully');
    }

    /**
     * Display the specified Products.
     * GET|HEAD /products/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Products $products */
        $products = $this->productsRepository->find($id);
        if (empty($products)) {
            return $this->sendError('Products not found');
        }

        $products->load('variants');

        return $this->sendResponse(new ProductsResource($products), 'Products retrieved successfully');
    }

    /**
     * Update the specified Products in storage.
     * PUT/PATCH /products/{id}
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update($id, Request $request)
    {

        $input = $request->validate(Products::$rules);

        /** @var Products $products */
        $products = $this->productsRepository->find($id);

        if (empty($products)) {
            return $this->sendError('Products not found');
        }

        $products = $this->productsRepository->update($input, $id);

        if ($request->has('variantId')){
            $variantFields = $request->validate(ProductVariant::$rules);
            $variant = $this->productVariantRepository->find($request->get('variantId'));

            if (empty($variant)) {
                return $this->sendError('Product variant not found');
            }
            $this->productVariantRepository->update($variantFields,$variant->id);
            $products = $products->load('latestUpdated');
        }

        return $this->sendResponse(new ProductsResource($products), 'Products updated successfully');
    }

    /**
     * Remove the specified Products from storage.
     * DELETE /products/{id}
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     *
     */
    public function destroy($id)
    {
        /** @var Products $products */
        $products = $this->productsRepository->find($id);

        if (empty($products)) {
            return $this->sendError('Products not found');
        }

        $products->delete();

        return $this->sendSuccess('Products deleted successfully');
    }
}
