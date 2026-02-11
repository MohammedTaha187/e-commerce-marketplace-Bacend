<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Brand\StoreBrandRequest;
use App\Http\Requests\Api\V1\Brand\UpdateBrandRequest;
use App\Http\Resources\Api\V1\BrandResource;
use App\Models\Brand;
use App\Services\Api\V1\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function __construct(protected FileUploadService $fileUploadService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => __('lang.Brands fetched successfully'),
            'data' => BrandResource::collection($brands),
            'meta' => [
                'current_page' => $brands->currentPage(),
                'last_page' => $brands->lastPage(),
                'per_page' => $brands->perPage(),
                'total' => $brands->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBrandRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data);

            $brand = Brand::create($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Brand created successfully'),
                'data' => new BrandResource($brand),
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        return response()->json([
            'success' => true,
            'message' => __('lang.Brand fetched successfully'),
            'data' => new BrandResource($brand),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        return DB::transaction(function () use ($request, $brand) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data, $brand);

            $brand->update($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Brand updated successfully'),
                'data' => new BrandResource($brand),
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        if ($brand->logo) {
            $this->fileUploadService->delete($brand->logo);
        }

        $brand->delete();

        return response()->json([
            'success' => true,
            'message' => __('lang.Brand deleted successfully'),
        ]);
    }

    /**
     * Handle image upload and deletion logic.
     */
    private function handleImageUpload(Request $request, array &$data, ?Brand $brand = null): void
    {
        if ($request->hasFile('logo')) {
            if ($brand && $brand->logo) {
                $this->fileUploadService->delete($brand->logo);
            }

            $data['logo'] = $this->fileUploadService->upload(
                $request->file('logo'),
                config('uploads.brands', 'brands')
            );
        }
    }
}
