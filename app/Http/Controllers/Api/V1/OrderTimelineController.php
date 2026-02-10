<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use App\Http\Requests\Api\V1\OrderTimeline\StoreOrderTimelineRequest;
use App\Http\Requests\Api\V1\OrderTimeline\UpdateOrderTimelineRequest;
use App\Models\OrderTimeline;

class OrderTimelineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderTimelineRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderTimeline $orderTimeline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderTimeline $orderTimeline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderTimelineRequest $request, OrderTimeline $orderTimeline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderTimeline $orderTimeline)
    {
        //
    }
}
