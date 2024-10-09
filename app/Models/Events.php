<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'banner_image',
        'description',
        'venue',
        'event_date',
    ];

    public function category(){
        return $this->belongsTo(EventCategory::class, 'category_id');
    }
}
