<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\StorageLimit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ContactedLeadList extends Model implements HasMedia
{
    use InteractsWithMedia, StorageLimit, HasFactory;

    protected $table = 'contacted_lead_list';

    protected $fillable = [
        'address',
        'docId',
        'title',
        'favourite',
        'card_image',
        'url',
        'user_id',
        'phone',
        'service',
        'organization',
        'name',
        'qr_code',
        'tag',
        'email'
    ];

    public $timestamps = false;

    /**
     * Media collection name
     */
    const CARD_IMAGE_PATH = 'contacted_leads/card_images';

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