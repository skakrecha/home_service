<?php
/*
 * File name: Address.php
 * Last modified: 2021.02.16 at 10:52:09
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Models;

use Eloquent as Model;
use App\Casts\AddressCast;
use App\PropertyCertificate;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;

/**
 * Class Address
 * @package App\Models
 * @version January 13, 2021, 8:02 pm UTC
 *
 * @property User user
 * @property integer id
 * @property string description
 * @property string address
 * @property double latitude
 * @property double longitude
 * @property boolean default
 * @property integer user_id
 */
class Address extends Model implements Castable, HasMedia
{
    use HasMediaTrait {
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }
    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'description' => 'max:255',
        'address' => 'required|max:255',
        'latitude' => 'required|numeric|min:-200|max:200',
        'longitude' => 'required|numeric|min:-200|max:200',
    ];
    public $table = 'addresses';
    public $fillable = [
        'description',
        'address',
        'latitude',
        'longitude',
        'default',
        'user_id'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'string',
        'address' => 'string',
        'latitude' => 'double',
        'longitude' => 'double',
        'default' => 'boolean',
        'user_id' => 'integer',
        'number_of_gas_appliances'=>'string',
        'number_of_electric_appliances'=>'string',
        'number_of_bedrooms'=>'string',
        'number_of_bathrooms'=>'string',
        'number_of_reception_room'=>'string',
        'property_media' => 'string',
    ];
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',
        'property_media',
    ];

    protected $hidden = [
        "created_at",
        "updated_at",
    ];

    
    /**
     * @return CastsAttributes|CastsInboundAttributes|string
     */
    public static function castUsing()
    {
        return AddressCast::class;
    }

    public function getCustomFieldsAttribute()
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            ->where('custom_fields.in_table', '=', true)
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    public function getPropertyMediaAttribute()
    {
        $image =null;
        if ($media = $this->getMedia('properties')->last()) {
            $image = $media->getFullUrl();
        }
        return $image;
    }

    /**
     * @return BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function certificates()
    {
        return $this->hasMany(PropertyCertificate::class, 'property_id');
    }


    /**
     * @param Media|null $media
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->sharpen(10);

        $this->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    /**
    * to generate media url in case of fallback will
    * return the file type icon
    * @param string $conversion
    * @return string url
    */
    public function getFirstMediaUrl($collectionName = 'default', $conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension, config('medialibrary.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName, $conversion));
        } else {
            return asset(config('medialibrary.icons_folder') . '/' . $extension . '.png');
        }
    }

    public function getHasMediaAttribute()
    {
        return $this->hasMedia('image') ? true : false;
    }
}
