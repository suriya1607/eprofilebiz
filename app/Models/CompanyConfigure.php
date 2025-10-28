<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyConfigure extends Model
{
    protected $fillable = [
        'name', 'logo', 'description', 'social_links','user_id'
    ];

    protected $casts = [
        'social_links' => 'array',
    ];
    use HasFactory;

}
