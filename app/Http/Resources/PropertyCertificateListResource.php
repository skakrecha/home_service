<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyCertificateListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $certificate_media = $this->getMedia('property_certificate');
        $media_url = [];
        foreach($certificate_media as $key => $media){
            $media_url[$key] = $media->getFullUrl();
        }

        return [
            'id' => $this->id,
            'property_id' => $this->property_id,
            'name' => $this->name,
            'expiry_date' => $this->expiry_date? Carbon::parse($this->expiry_date) : null,
            'start_date' =>$this->start_date? Carbon::parse($this->start_date) : null,
            'days_left' => $this->expiry_date ? Carbon::parse($this->expiry_date)->diffForHumans() : null,
            'last_paid_amount' => $this->last_paid_amount,
            'notes' => $this->notes,
            'media' => $media_url,
        ];
    }
}
