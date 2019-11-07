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

  private $order;
  private $filesServices;

  public function __construct(FilesServices $filesServices)
  {
    $this->filesServices = $filesServices;
  }

  public function setOrder($orderId)
  {
    $this->order = Order::find($orderId);
  }

  public function getOrder()
  {
    return $this->order;
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
    $order->email = $request->get('email');
    $order->phone = $request->get('phone');
    $order->frame_type = $request->get('frame_type');

    if ($request->hasFile('frame_image')) {
      // Get full filename
      $fileName = $request->file('frame_image')->getClientOriginalName();

      // Remove space from filename
      $filenameWithExt = str_replace(' ', '', $fileName);

      //Extract filename only
      $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

      //Extract extenstion only
      $extension = $request->file('frame_image')->getClientOriginalExtension();

      //Combine again with timestamp in the middle to differentiate files with same filename.
      $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;

      $path = $request->file('frame_image')->storeAs('public/frame_images', $filenameToStore);

      $storagePath = asset("storage/frame_images/$filenameToStore");

      // $nameAndPath = $this->filesServices->upload('frame_images',$request->file('frame_image'));

      $order->frame_image = $filenameToStore;
      
      $order->frame_image_path = $storagePath;
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
        "email" => $order->email,
        "phone" => $order->phone,
        "frame_type" => $frameTypeModel->frame_type,
        "frame_image" => $order->frame_image,
        "frame_image_path" => $order->frame_image_path,
        "frame_dimension" => $frameDimensionModel->frame_dimension . '(₦' . $frameDimensionModel->price . ')',
        "shipping_addr" => $order->shipping_addr,
        "state" => $order->state,
        "extra_note" => $order->extra_note,
        "is_paid" => $order->is_paid,
        "is_received" => $order->is_received,
        "is_processing" => $order->is_processing,
        "is_shipped" => $order->is_shipped,
        "is_delivered" => $order->is_delivered,
        "is_completed" => $order->is_completed,
        "id" => $order->id
      ],

    ], 200);
  }


  public function updateOrderPaymentStatus($order_id, Request $request)
  {

    $this->validate($request, [
      'is_paid' => 'required'
    ]);

    $this->setOrder($order_id);

    $this->getOrder()->update([
      'is_paid'  => $request->get('is_paid')
    ]);

    return success("Order payment verified successfully. Thanks for your patronage!");
  }
}