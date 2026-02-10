<?php

namespace App\Models;

use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerProfile extends Model
{
    /** @use HasFactory<\Database\Factories\SellerProfileFactory> */
    use HasFactory, Translatable;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $translatable = ['store_name', 'store_description'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
