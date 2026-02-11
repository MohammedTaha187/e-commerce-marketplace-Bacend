<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Review\StoreReviewRequest;
use App\Http\Requests\Api\V1\Review\UpdateReviewRequest;
use App\Http\Resources\Api\V1\ReviewResource;
use App\Models\Review;
use App\Services\Api\V1\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function __construct(protected FileUploadService $fileUploadService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Reviews retrieved successfully',
            'data' => ReviewResource::collection($reviews),
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data);

            $review = Review::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Review created successfully',
                'data' => new ReviewResource($review),
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Review retrieved successfully',
            'data' => new ReviewResource($review),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        return DB::transaction(function () use ($request, $review) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data, $review);

            $review->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Review updated successfully',
                'data' => new ReviewResource($review),
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        if ($review->image) {
            $this->fileUploadService->delete($review->image);
        }

        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted successfully',
        ]);
    }

    private function handleImageUpload(Request $request, array &$data, ?Review $review = null): void
    {
        if ($request->hasFile('image')) {
            if ($review && $review->image) {
                $this->fileUploadService->delete($review->image);
            }

            $data['image'] = $this->fileUploadService->upload(
                $request->file('image'),
                config('uploads.reviews', 'reviews')
            );
        }
    }
}
