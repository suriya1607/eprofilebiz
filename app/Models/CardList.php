<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CardList extends Model
{
    use HasFactory;

    protected $table = 'cards_list'; // your table name

    protected $fillable = [
        'address',
        'card_image',
        'email',
        'name',
        'organization',
        'phone',
        'qr_code',
        'scannedLocation',
        'scannedLocationGeoPoint',
        'service',
        'tag',
        'title',
        'url',
        'user_id',
        'favourite'
    ];
}