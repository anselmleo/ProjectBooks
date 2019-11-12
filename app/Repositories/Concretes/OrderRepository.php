<?php


namespace App\Repositories\Concretes;

use App\Repositories\Contracts\IOrderRepository;
use App\Models\User;
use App\Models\Order;
use App\Services\FilesServices;
use App\Utils\Response;
use Exception;
use App\Jobs\SendOrderPostedEmailJob;
use App\Jobs\SendWelcomeEmailJob;


class OrderRepository implements IOrderRepository
{
  use Response;

  public function order(object $request)
  {
    
    try {


      if (!User::where('email', $request->get('email'))->first()) {


        $user = new User;

        $user->email = $request->get('email');
      
        $user->phone = $request->get('phone');

        $user->save();
        
        $isNull = is_null($user);
        
        if ($isNull)
          throw new Exception("Could not create a user. Null parameter received");
        dd('i got here');
        dispatch(new SendWelcomeEmailJob($user));
      }


      $order = new Order;
      
      $order->full_name = $request->get('full_name');
      
      $order->email = $request->get('email');
      
      $order->phone = $request->get('phone');
      
      $order->frame_type = $request->get('frame_type');
      
      if ($request->hasFile($request->get('frame_image'))) {
        
        $filesServices = new FilesServices;
        
        $nameAndPath = $filesServices->upload('fotomi-api/frame_images', $request
                                      ->file('frame_image'), 's3');
      
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

      $isNull = is_null($order);
      
      if ($isNull)
        throw new Exception("Could not create order. Null parameter received");

        dispatch(new SendOrderPostedEmailJob($order));

      $orderWithFrameData = $order->with('frameType', 'frameDimension')
        ->where('id', $order->id)->first();

      return $orderWithFrameData;
    
    } catch (Exception $e) {
      report($e);
      $order->delete();
      throw new Exception("Unable to post order");
    }
  }

}
