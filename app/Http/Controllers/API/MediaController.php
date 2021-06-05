<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function destroy(Media $media)
    {

        $media->delete();

        return response()->json(['status' => true, 'message' => 'media deleted successfully']);
    }
}
