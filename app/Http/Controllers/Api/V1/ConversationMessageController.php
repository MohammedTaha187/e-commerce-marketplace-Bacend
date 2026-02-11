<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ConversationMessage\StoreConversationMessageRequest;
use App\Http\Requests\Api\V1\ConversationMessage\UpdateConversationMessageRequest;
use App\Models\ConversationMessage;

class ConversationMessageController extends Controller
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
    public function store(StoreConversationMessageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ConversationMessage $conversationMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ConversationMessage $conversationMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConversationMessageRequest $request, ConversationMessage $conversationMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConversationMessage $conversationMessage)
    {
        //
    }
}
