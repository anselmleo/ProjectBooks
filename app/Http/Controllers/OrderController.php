<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;
use App\Utils\Rules;

class OrderController extends Controller
{
  public function order(Request $request)
  {
    // $payload = request()->all();

    // $validator = Validator::make($payload, Rules::get('POST_ORDER'));

    // if ($validator->fails()) {
    //   return $this->validationErrors($validator->getMessageBag()->all());
    // }

    $this->validate($request, [
      'full_name' => 'required|string',
      'email' => 'required|email',
      'phone' => 'required|digits:11',
      'frame_type' => 'required|string',
      'frame_image' => 'required_without:frame_text|image|mimes:jpeg,png,jpg',
      'frame_text' => 'required_without:frame_image|string',
      'frame_dimension' => 'required|string',
      'shipping_addr' => 'required|string',
      'state' => 'required|string',
      'extra_note' => 'string'
    ]);

    if(!User::where('email', $request->get('email'))->first()) {
      $user = new User;
      $user->full_name = $request->get('full_name');
      $user->email = $request->get('email');
      $user->phone = $request->get('phone');
      $user->save();
    }  
    
    $order = new Order;
    $order->full_name = $request->get('full_name');
    $order->frame_type = $request->get('frame_type');

    if ($request->hasFile('frame_image')) {
      //Get full filename
      $filenameWithExt = $request->file('frame_image')->getClientOriginalName();

      //Extract filename only
      $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

      //Extract extenstion only
      $extension = $request->file('frame_image')->getClientOriginalExtension();

      //Combine again with timestamp in the middle to differentiate files with same filename.
      $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;
      
      $request->file('frame_image')->storeAs('public/frame_images', $filenameToStore);

      $order->frame_image = $filenameToStore;

    } else {
      $order->frame_text = $request->get('frame_text');
    }

    $order->frame_dimension = $request->get('frame_dimension');
    $order->shipping_addr = $request->get('shipping_addr');
    $order->state = $request->get('state');
    $order->extra_note = $request->get('extra_note');
    $order->save();

    return response()->json([
      "status" => true,
      "payload" => $order
    ], 200);
  }
}