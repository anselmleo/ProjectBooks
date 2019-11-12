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
use App\Utils\Response;
use App\Services\FilesServices;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts\IOrderRepository;
use Exception;

class OrderController extends Controller
{
  use Response;

  private $order;
  private $filesServices;
  private $orderRepository;

  public function __construct(IOrderRepository $orderRepository, FilesServices $filesServices)
  {
    $this->orderRepository = $orderRepository;
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
    $validator = Validator::make(request()->all(), Rules::get('POST_ORDER'));

    if ($validator->fails()) {
      return $this->validationErrors($validator->getMessageBag()->all());
    }

    try {
      $order = $this->orderRepository->order($request);
      return $this->withData($order);    

    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }

    

    
  }

}