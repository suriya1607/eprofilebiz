<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\StorageLimit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CardList extends Model implements HasMedia
{
    use InteractsWithMedia, StorageLimit, HasFactory;

    protected $table = 'cards_list';

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

    public $timestamps = false;

    /**
     * Media collection name
     */
    const CARD_IMAGE_PATH = 'cards/card_images';

    /**
     * Optional accessor for image url
     */
    public function getCardImageUrlAttribute(): string
    {
        /** @var Media $media */
        $media = $this->getMedia(self::CARD_IMAGE_PATH)->first();

        if ($media !== null) {
            return str_replace('\\', '/', $media->getFullUrl());
        }

        return '';
    }
}