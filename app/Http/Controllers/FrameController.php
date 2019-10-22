<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FrameDimension;
use App\Models\FrameType;


class FrameController extends Controller
{
  public function getPricing(Request $request)
  {
    $regulars = FrameDimension::where('frame_type_id', 1)->get();
    foreach ($regulars as $regular) {
      $regular['name'] = $regular['frame_dimension'] . '(₦' . $regular['price'] . ')';
    }

    $illustrations = FrameDimension::where('frame_type_id', 2)->get();
    foreach ($illustrations as $illustration) {
      $illustration['name'] = $illustration['frame_dimension'] . '(₦' . $illustration['price'] . ')';
    }


    $quotes = FrameDimension::where('frame_type_id', 3)->get();
    foreach ($quotes as $quote) {
      $quote['name'] = $quote['frame_dimension'] . '(₦' . $quote['price'] . ')';
    }

    return response()->json([ 
      'payload' => 
        [
          'Regular'=>$regulars, 
          'Illustration'=>$illustrations, 
          'Quote'=>$quotes
        ] 
    ]);
  }
}
