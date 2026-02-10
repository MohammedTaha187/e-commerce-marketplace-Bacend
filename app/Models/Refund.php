<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    /** @use HasFactory<\Database\Factories\RefundFactory> */
    use HasFactory;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
