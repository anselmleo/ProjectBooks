<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Profile;
use App\Models\FrameType;
use App\Models\FrameDimension;
use Illuminate\Support\Facades\Validator;
use App\Utils\Rules;
use App\Services\FilesServices;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
  private $user;
  private $order;
  private $filesServices;

  public function __construct(FilesServices $filesServices)
  {
    $this->filesServices = $filesServices;
  }

  public function setOrder($order_id)
  {
    $this->order = Order::find($order_id);
  }

  public function setUser($user_id)
  {
    $this->user = User::find($user_id);
  }

  public function getUser()
  {
    return $this->user;
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
      $user->email = $request->get('email');
      $user->phone = $request->get('phone');
      $user->save();

      $isNull = is_null($user);
      if ($isNull)
        throw new Exception("Could not create a user. Null parameter received");

      $user_id = $user->id;
      
      $this->setUser($user_id);
      
      $user = $this->getUser();
      
      $this->getUser()->profile()->create([
        'full_name' => $request->get('full_name'),
        'avatar' => Profile::AVATAR
      ]);
    }  
    
    $order = new Order;
    $order->full_name = $request->get('full_name');
    $order->email = $request->get('email');
    $order->phone = $request->get('phone');
    $order->frame_type = $request->get('frame_type');

    if ($request->hasFile('frame_image')) {
      // Get full filename
      // $fileName = $request->file('frame_image')->getClientOriginalName();

      // // Remove space from filename
      // $filenameWithExt = str_replace(' ', '', $fileName);

      // //Extract filename only
      // $filenameWithoutExt = pathinfo($filenameWithExt, PATHINFO_FILENAME);

      // //Extract extenstion only
      // $extension = $request->file('frame_image')->getClientOriginalExtension();

      // //Combine again with timestamp in the middle to differentiate files with same filename.
      // $filenameToStore = $filenameWithoutExt . '_' . time() . '.' . $extension;

      $nameAndPath = $this->filesServices->upload('fotomi-api/frame_images', $request->file('frame_image'), 's3');

      $order->frame_image = $nameAndPath[0];
      
      $order->frame_image_path = $nameAndPath[1];

    } else {

      $order->frame_text = $request->get('frame_text');
    }
    $order->frame_dimension = $request->get('frame_dimension');
    $order->shipping_addr = $request->get('shipping_addr');
    $order->state = $request->get('state');
    $order->extra_note = $request->get('extra_note');
    $order->save();

    $orderWith = $order->with('frameType', 'frameDimension')->where('id', $order->id)->first();

    return $this->withData($orderWith);    
  }

}