<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VcardSendersList extends Model
{
    use HasFactory;

    protected $table = 'vcardsenders_list';

    protected $fillable = [
        'vcard_id',
        'senders_name',
        'senders_number',
    ];

}
