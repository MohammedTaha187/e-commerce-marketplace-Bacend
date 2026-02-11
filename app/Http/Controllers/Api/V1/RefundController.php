<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Refund\StoreRefundRequest;
use App\Http\Requests\Api\V1\Refund\UpdateRefundRequest;
use App\Http\Resources\Api\V1\RefundResource;
use App\Models\Refund;
use App\Services\Api\V1\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RefundController extends Controller
{
    public function __construct(protected FileUploadService $fileUploadService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $refunds = Refund::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => __('lang.Refunds fetched successfully'),
            'data' => RefundResource::collection($refunds),
            'meta' => [
                'current_page' => $refunds->currentPage(),
                'last_page' => $refunds->lastPage(),
                'per_page' => $refunds->perPage(),
                'total' => $refunds->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRefundRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data);

            $refund = Refund::create($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Refund created successfully'),
                'data' => new RefundResource($refund),
            ]);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Refund $refund)
    {
        return response()->json([
            'success' => true,
            'message' => __('lang.Refund fetched successfully'),
            'data' => new RefundResource($refund),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRefundRequest $request, Refund $refund)
    {
        return DB::transaction(function () use ($request, $refund) {
            $data = $request->validated();

            $this->handleImageUpload($request, $data, $refund);

            $refund->update($data);

            return response()->json([
                'success' => true,
                'message' => __('lang.Refund updated successfully'),
                'data' => new RefundResource($refund),
            ]);
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Refund $refund)
    {
        if ($refund->image) {
            $this->fileUploadService->delete($refund->image);
        }

        $refund->delete();

        return response()->json([
            'success' => true,
            'message' => __('lang.Refund deleted successfully'),
        ]);
    }

    private function handleImageUpload(Request $request, array &$data, ?Refund $refund = null): void
    {
        if ($request->hasFile('image')) {
            if ($refund && $refund->image) {
                $this->fileUploadService->delete($refund->image);
            }

            $data['image'] = $this->fileUploadService->upload(
                $request->file('image'),
                config('uploads.refunds', 'refunds')
            );
        }
    }
}
