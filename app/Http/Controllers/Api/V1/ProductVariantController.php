<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ProductVariant\StoreProductVariantRequest;
use App\Http\Requests\Api\V1\ProductVariant\UpdateProductVariantRequest;
use App\Http\Resources\Api\V1\ProductVariantResource;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Api\V1\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    public function __construct(protected FileUploadService $fileUploadService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $productVariants = $product->variants()->orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => __('lang.Product variants fetched successfully'),
            'data' => ProductVariantResource::collection($productVariants),
            'meta' => [
                'current_page' => $productVariants->currentPage(),
                'last_page' => $productVariants->lastPage(),
                'per_page' => $productVariants->perPage(),
                'total' => $productVariants->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductVariantRequest $request, Product $product)
    {
        return DB::transaction(function () use ($request, $product) {
            $data = $request->validated();
            $data['product_id'] = $product->id;

            $this->handleImageUpload($request, $data);

            $productVariant = ProductVariant::create($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Product variant created successfully'),
                'data' => new ProductVariantResource($productVariant),
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductVariant $productVariant)
    {
        return response()->json([
            'success' => true,
            'message' => __('lang.Product variant fetched successfully'),
            'data' => new ProductVariantResource($productVariant),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductVariantRequest $request, ProductVariant $productVariant)
    {
        return DB::transaction(function () use ($request, $productVariant) {
            $data = $request->validated();
            $this->handleImageUpload($request, $data, $productVariant);
            $productVariant->update($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Product variant updated successfully'),
                'data' => new ProductVariantResource($productVariant),
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductVariant $productVariant)
    {
        if ($productVariant->image) {
            $this->fileUploadService->delete($productVariant->image);
        }
        $productVariant->delete();

        return response()->json([
            'success' => true,
            'message' => __('lang.Product variant deleted successfully'),
        ]);
    }

    private function handleImageUpload(Request $request, array &$data, ?ProductVariant $productVariant = null): void
    {
        if ($request->hasFile('image')) {
            if ($productVariant && $productVariant->image) {
                $this->fileUploadService->delete($productVariant->image);
            }

            $data['image'] = $this->fileUploadService->upload(
                $request->file('image'),
                config('uploads.product_variants', 'product_variants')
            );
        }
    }
}
