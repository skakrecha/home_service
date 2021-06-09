<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class PropertyCertificate extends Model implements HasMedia
{
    use HasMediaTrait;
    
    public function property()
    {
        return $this->belongsTo(\App\Models\Address::class, 'property_id');
    }
}
