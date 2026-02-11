<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;
use App\Http\Requests\Api\V1\Category\UpdateCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use App\Services\Api\V1\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct(protected FileUploadService $fileUploadService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => __('lang.Categories fetched successfully'),
            'data' => CategoryResource::collection($categories),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data);

            $category = Category::create($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Category created successfully'),
                'data' => new CategoryResource($category),
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'success' => true,
            'message' => __('lang.Category fetched successfully'),
            'data' => new CategoryResource($category),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        return DB::transaction(function () use ($request, $category) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data, $category);

            $category->update($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Category updated successfully'),
                'data' => new CategoryResource($category),
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->image) {
            $this->fileUploadService->delete($category->image);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => __('lang.Category deleted successfully'),
        ]);
    }

    private function handleImageUpload(Request $request, array &$data, ?Category $category = null): void
    {
        if ($request->hasFile('image')) {
            if ($category && $category->image) {
                $this->fileUploadService->delete($category->image);
            }

            $data['image'] = $this->fileUploadService->upload(
                $request->file('image'),
                config('uploads.categories', 'categories')
            );
        }
    }
}
