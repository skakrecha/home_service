<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;

class PropertyListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $property_media = $this->getMedia('properties');
        $media_url = [];
        foreach($property_media as $key => $media){
            $media_url[$key] = $media->getFullUrl();
        }

        return [
            'id' => $this->id,
            'name' => $this->description,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'property_type' => $this->property_type,
            'number_of_gas_appliances' => $this->number_of_gas_appliances,
            'number_of_electric_appliances' => $this->number_of_electric_appliances,
            'number_of_bedrooms' => $this->number_of_bedrooms,
            'number_of_reception_room' => $this->number_of_reception_room,
            'boiler_type' => $this->boiler_type,
            'furnished_type' => $this->furnished_type,
            'outside_space' => $this->outside_space,
            'parking' => $this->parking,
            'notes' => $this->notes,
            'media' => $media_url,
        ];
    }
}
