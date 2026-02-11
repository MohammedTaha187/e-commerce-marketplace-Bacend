<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\SupportMessage\StoreSupportMessageRequest;
use App\Http\Requests\Api\V1\SupportMessage\UpdateSupportMessageRequest;
use App\Http\Resources\Api\V1\SupportMessageResource;
use App\Models\SupportMessage;

class SupportMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supportMessages = SupportMessage::orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Support messages retrieved successfully',
            'data' => SupportMessageResource::collection($supportMessages),
            'meta' => [
                'current_page' => $supportMessages->currentPage(),
                'last_page' => $supportMessages->lastPage(),
                'per_page' => $supportMessages->perPage(),
                'total' => $supportMessages->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupportMessageRequest $request)
    {
        $supportMessage = SupportMessage::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Support message created successfully',
            'data' => new SupportMessageResource($supportMessage),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SupportMessage $supportMessage)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Support message retrieved successfully',
            'data' => new SupportMessageResource($supportMessage),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupportMessageRequest $request, SupportMessage $supportMessage)
    {
        $supportMessage->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Support message updated successfully',
            'data' => new SupportMessageResource($supportMessage),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupportMessage $supportMessage)
    {
        $supportMessage->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Support message deleted successfully',
        ]);
    }
}
