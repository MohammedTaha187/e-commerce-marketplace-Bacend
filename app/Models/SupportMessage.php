<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    /** @use HasFactory<\Database\Factories\SupportMessageFactory> */
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
}
