<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\PropertyCertificate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyCertificateListResource;
use App\Models\Address;

class PropertyCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Address $address)
    {
        $certificates = $address->certificates;

        return PropertyCertificateListResource::collection($certificates);
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
            'property_id' => 'required',
            'name' => 'required',
            'expiry_date' => 'required',
            'start_date' => 'sometimes',
            'last_paid_amount' => 'sometimes',
            'notes' => 'sometimes',
        ]);
            
        $property_certificate = new PropertyCertificate();
        $property_certificate->property_id = $request->property_id;
        $property_certificate->name = $request->name;
        $property_certificate->expiry_date = $request->expiry_date ? Carbon::parse($request->expiry_date) : null;
        $property_certificate->start_date = $request->start_date ? Carbon::parse($request->start_date) : null;
        $property_certificate->last_paid_amount = $request->last_paid_amount;
        $property_certificate->notes = $request->notes;
        $property_certificate->save();

        if($request->certificate_media != null){
            foreach($request->certificate_media as $index => $media){
                $property_certificate->addMedia($media)
                            ->usingName('certificate_image')
                            ->toMediaCollection('property_certificate');
            }
        }


        return response()->json(['status' => true, 'message' => 'Property Certificate Created Successfully.']);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $property_certificate = PropertyCertificate::find($id);

        return new PropertyCertificateListResource($property_certificate);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PropertyCertificate $property_certificate)
    {
        $request->validate([
            'property_id' => 'required',
            'name' => 'required',
            'expiry_date' => 'required',
            'start_date' => 'sometimes',
            'last_paid_amount' => 'sometimes',
            'notes' => 'sometimes',
        ]);
            
        $property_certificate = new PropertyCertificate();
        $property_certificate->property_id = $request->property_id;
        $property_certificate->name = $request->name;
        $property_certificate->expiry_date = $request->expiry_date ? Carbon::parse($request->expiry_date) : null;
        $property_certificate->start_date = $request->start_date ? Carbon::parse($request->start_date) : null;
        $property_certificate->last_paid_amount = $request->last_paid_amount;
        $property_certificate->notes = $request->notes;
        $property_certificate->save();

        if($request->certificate_media != null){
            foreach($request->certificate_media as $index => $media){
                $property_certificate->addMedia($media)
                            ->usingName('certificate_image')
                            ->toMediaCollection('property_certificate');
            }
        }
        
        return response()->json(['status' => true, 'message' => 'Property Certificate Updated Successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $property_certificate = PropertyCertificate::find($id);

        $property_certificate->delete();

        return response()->json(['status' => true, 'message' => 'Property Certificate Deleted Successfully.']);
    }
}
