<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\StoreProductRequest;
use App\Http\Requests\Api\V1\Product\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use App\Services\Api\V1\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct(protected FileUploadService $fileUploadService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prods = Product::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => __('lang.Products fetched successfully'),
            'data' => ProductResource::collection($prods),
            'meta' => [
                'current_page' => $prods->currentPage(),
                'last_page' => $prods->lastPage(),
                'per_page' => $prods->perPage(),
                'total' => $prods->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data);

            $product = Product::create($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Product created successfully'),
                'data' => new ProductResource($product),
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'message' => __('lang.Product fetched successfully'),
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        return DB::transaction(function () use ($request, $product) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data, $product);

            $product->update($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Product updated successfully'),
                'data' => new ProductResource($product),
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            $this->fileUploadService->delete($product->image);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => __('lang.Product deleted successfully'),
        ]);
    }

    /**
     * Handle image upload and deletion logic.
     */
    private function handleImageUpload(Request $request, array &$data, ?Product $product = null): void
    {
        if ($request->hasFile('image')) {
            if ($product && $product->image) {
                $this->fileUploadService->delete($product->image);
            }

            $data['image'] = $this->fileUploadService->upload(
                $request->file('image'),
                config('uploads.products', 'products')
            );
        }
    }
}
