<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\PropertyListResource;
use App\Models\Address;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $properties = $user->addresses()->with(['user', 'certificates'])->get();

        return PropertyListResource::collection($properties);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'property_type' => 'sometimes',
            'number_of_gas_appliances' => 'sometimes',
            'number_of_electric_appliances' => 'sometimes',
            'number_of_bedrooms' => 'sometimes',
            'number_of_reception_room' => 'sometimes',
            'boiler_type' => 'sometimes',
            'furnished_type' => 'sometimes',
            'outside_space' => 'sometimes',
            'parking' => 'sometimes',
            'notes' => 'sometimes',
        ]);

        $user = $request->user();

        $property = new Address();
        $property->description = $request->name;
        $property->address = $request->address;
        $property->latitude = $request->latitude;
        $property->longitude = $request->longitude;
        $property->property_type = $request->property_type;
        $property->number_of_gas_appliances = $request->number_of_gas_appliances;
        $property->number_of_electric_appliances = $request->number_of_electric_appliances;
        $property->number_of_bedrooms = $request->number_of_bedrooms;
        $property->number_of_reception_room = $request->number_of_reception_room;
        $property->boiler_type = $request->boiler_type;
        $property->furnished_type = $request->furnished_type;
        $property->outside_space = $request->outside_space;
        $property->parking = $request->parking;
        $property->notes = $request->notes;
        $property->user_id = $user->id;
        $property->save();

        if($request->property_media != null){
            foreach($request->property_media as $index => $media){
                $property->addMedia($media)
                            ->usingName('property_image')
                            ->toMediaCollection('properties');
            }
        }

        return response()->json(['status' => true, 'message' => 'Property Created Successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $property = Address::find($id);

        return new PropertyListResource($property);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Address $address)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'property_type' => 'sometimes',
            'number_of_gas_appliances' => 'sometimes',
            'number_of_electric_appliances' => 'sometimes',
            'number_of_bedrooms' => 'sometimes',
            'number_of_reception_room' => 'sometimes',
            'boiler_type' => 'sometimes',
            'furnished_type' => 'sometimes',
            'outside_space' => 'sometimes',
            'parking' => 'sometimes',
            'notes' => 'sometimes',
        ]);

        $user = $request->user();

        $address->description = $request->name;
        $address->address = $request->address;
        $address->latitude = $request->latitude;
        $address->longitude = $request->longitude;
        $address->property_type = $request->property_type;
        $address->number_of_gas_appliances = $request->number_of_gas_appliances;
        $address->number_of_electric_appliances = $request->number_of_electric_appliances;
        $address->number_of_bedrooms = $request->number_of_bedrooms;
        $address->number_of_reception_room = $request->number_of_reception_room;
        $address->boiler_type = $request->boiler_type;
        $address->furnished_type = $request->furnished_type;
        $address->outside_space = $request->outside_space;
        $address->parking = $request->parking;
        $address->notes = $request->notes;
        $address->user_id = $user->id;
        $address->update();

        if($request->property_media != null){
            foreach($request->property_media as $index => $media){
                $address->addMedia($media)
                            ->usingName('property_image')
                            ->toMediaCollection('properties');
            }
        }

        return response()->json(['status' => true, 'message' => 'Property Updated Successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $property = Address::find($id);

        $property->delete();

        return response()->json(['status' => true, 'message' => 'Property Deleted Successfully.']);
    }
}
