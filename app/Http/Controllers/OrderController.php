<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\FrameType;
use App\Models\FrameDimension;
use Illuminate\Support\Facades\Validator;
use App\Utils\Rules;
use App\Services\FilesServices;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{

  private $filesServices;

  public function __construct(FilesServices $filesServices)
  {
    $this->filesServices = $filesServices;
  }
  public function order(Request $request)
  {
    // $payload = request()->all();

    // $validator = Validator::make($payload, Rules::get('POST_ORDER'));

    // if ($validator->fails()) {
    //   return $this->validationErrors($validator->getMessageBag()->all());
    // }

    // try {
    //   $order = $this->order;
    // } catch (Exception $e) {
    //   return $this->error($e->getMessage());
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
    $order->full_name = $request->get('email');
    $order->full_name = $request->get('phone');
    $order->frame_type = $request->get('frame_type');

    if ($request->hasFile('frame_image')) {
      //Get full filename
      // $filenameWithExt = $request->file('frame_image')->getClientOriginalName();

      // //Extract filename only
      // $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

      // //Extract extenstion only
      // $extension = $request->file('frame_image')->getClientOriginalExtension();

      // //Combine again with timestamp in the middle to differentiate files with same filename.
      // $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;

      $nameAndPath = $this->filesServices->upload('frame_images', $request->file('frame_image'));
      
      // $imageLink = $request->file('frame_image')->storeAs('public/frame_images', $filenameToStore);

      $imageurl = asset('public/storage/'.$nameAndPath[1]);

      $order->frame_image = $nameAndPath[0];
      $order->frame_image_path = Storage::url($nameAndPath[1]);
    } else {
      $order->frame_text = $request->get('frame_text');
    }
    $order->frame_dimension = $request->get('frame_dimension');
    $order->shipping_addr = $request->get('shipping_addr');
    $order->state = $request->get('state');
    $order->extra_note = $request->get('extra_note');
    $order->save();

    $frameType = $order->frame_type;
    $frameTypeModel = FrameType::find($frameType);

    $frameDimension = $order->frame_dimension;
    $frameDimensionModel = FrameDimension::find($frameDimension);
    

    return response()->json([
      "status" => true,
      "payload" => [ 
        "full_name" => $order->full_name,
        "full_name" => $order->email,
        "full_name" => $order->phone,
        "frame_type" => $frameTypeModel->frame_type,
        "frame_image" => $order->frame_image,
        "frame_image_path" => $order->frame_image_path,
        "frame_dimension" => $frameDimensionModel->frame_dimension . '(₦' . $frameDimensionModel->price . ')',
        "shipping_addr" => $order->shipping_addr,
        "state" => $order->state,
        "extra_note" => $order->extra_note,
        "is_paid" => $order->is_paid,
        "id" => $order->id
      ],

    ], 200);
  }
}