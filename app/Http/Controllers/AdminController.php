<?php


namespace App\Http\Controllers;

use App\Repositories\Contracts\IAdminRepository;
use App\Models\Order;
use App\Models\User;
use App\Utils\Rules;
use Exception;

class AdminController extends Controller
{
  /**
   * @var IAdminRepository
   */
  private $adminRepository;
  private $user;
  private $order;

  public function __construct(IAdminRepository $adminRepository)
  {
    // $this->middleware('auth:api');
    $this->adminRepository = $adminRepository;
  }

  /**
   * @OA\Get(
   *     path="/utils/cities",
   *     operationId="cities",
   *     tags={"Common"},
   *     security={{"authorization_token": {}}},
   *     summary="Get available cities",
   *     description="",
   *     @OA\Response(
   *         response="200",
   *         description="Returns response object",
   *         @OA\JsonContent()
   *     ),
   * )
   */
  public function getCities()
  {
    try {
      $response = $this->adminRepository->getCities();
      return $this->withData($response);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  /**
   * @OA\Get(
   *     path="/utils/categories",
   *     operationId="categories",
   *     tags={"Common"},
   *     security={{"authorization_token": {}}},
   *     summary="Get available categories",
   *     description="",
   *     @OA\Response(
   *         response="200",
   *         description="Returns response object",
   *         @OA\JsonContent()
   *     ),
   * )
   */
  public function getCategories()
  {
    try {
      $response = $this->adminRepository->getCategories();
      return $this->withData($response);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }


  //////////////////////////////////////////////////////////////////////////////
  //////////////////////////////////////////////////////////////////////////////
  // NEW CODES TO BE REFACTORED //

  public function getOrder():Order
  {
    return $this->order;
  }

  public function setOrder($order_id):void
  {
    $this->order = Order::find($order_id);
  }
  /**
   * Get all orders
   * @return Orders
   * @throws Exception
   */
  public function getAllOrders() 
  {
    try {
      $allOrders = Order::with('frameType', 'frameDimension')->get();
      return $this->withData($allOrders);
    } catch (Exception $e) {
      return response()->json([
        'error' => $e
      ]);
    }
  }

  /**
   * Get all users
   * @return Users
   * @throws Exception
   */
  public function getAllUsers()
  {
    try {
      $allUsers = User::all();
      return $this->withData($allUsers);
    } catch (Exception $e) {
      return response()->json([
        'error' => $e
      ]);
    }
  }

  public function payOrder($order_id)
  {
    try {

      $this->setOrder($order_id);

      $order = $this->getOrder();

      $isNull = is_null($this->getOrder());

      if ($isNull)
        throw new Exception("Could not get order, please try again!");

      $updateOrderPaymentStatus = $this->getOrder()->is_paid;

      if ($updateOrderPaymentStatus)
        throw new Exception("Order has already been marked as paid");

      $updateOrderPaymentStatus = $this->getOrder()->update([
        'order_status' => 'Pending',
        'is_paid' => true
      ]);
      
      $order = $order->refresh();
      
      return $this->withSuccessAndData("Order successfully marked as paid", $order);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }
  
  public function processOrder($order_id) 
  {
    try {

      $this->setOrder($order_id);

      $order = $this->getOrder();
    
      $isNull = is_null($this->getOrder());
    
      if($isNull)
        throw new Exception ("Could not get order, please try again!");

      $isReceived = $this->getOrder()->is_received;
      
      if(!$isReceived)
        throw new Exception ("Order has to be recieved before processing");

      $isProcessing = $this->getOrder()->is_processing;
      
      if($isProcessing)
        throw new Exception ("Order is already marked as being processed");

      $isProcessing = $this->getOrder()->update([
        'order_status' => 'Processing',
        'is_processing' => true
      ]);

      $order = $order->refresh();

      return $this->withSuccessAndData("successfully marked order as processing", $order);

    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }

  
  public function receiveOrder($order_id) 
  {
    try {

      $this->setOrder($order_id);

      $order = $this->getOrder();
    
      $isNull = is_null($this->getOrder());
    
      if($isNull)
        throw new Exception ("Could not get order, please try again!");

      $isReceived = $this->getOrder()->is_received;

      if($isReceived)
        throw new Exception ("Order has already been marked as received!");

      $isReceived = $this->getOrder()->update([
        'order_status' => 'Received',
        'is_received' => true
      ]);

      $order = $order->refresh();
      
      return $this->withSuccessAndData("successfully marked order as received", $order);
      
    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }


  public function shipOrder($order_id) 
  {
    try {

      $this->setOrder($order_id);
      
      $order = $this->getOrder();

      $isNull = is_null($this->getOrder());
    
      if($isNull)
        throw new Exception ("Could not get order, please try again!");

      $isProcessing = $this->getOrder()->is_processing;
      
      if(!$isProcessing)
        throw new Exception ("Order has to be processed before shipping");

      $isShipped = $this->getOrder()->is_shipped;
      
      if($isShipped)
        throw new Exception ("Order is already marked as being shipped");

      $isProcessing = $this->getOrder()->update([
        'order_status' => 'Shipped',
        'is_shipped' => true
      ]);

      $order = $order->refresh();

      return $this->withSuccessAndData("successfully marked order as shipped", $order);

    } catch (Exception $e) {
      
      return $this->error($e->getMessage());
    }
  }
  

  public function deliverOrder($order_id) 
  {
    try {

      $this->setOrder($order_id);
      
      $order = $this->getOrder();

      $isNull = is_null($this->getOrder());
    
      if($isNull)
        throw new Exception ("Could not get order, please try again!");

      $isShipped = $this->getOrder()->is_shipped;
      
      if(!$isShipped)
        throw new Exception ("Order has to be shipped before delivery");

      $isDelivered = $this->getOrder()->is_delivered;
      
      if($isDelivered)
        throw new Exception ("Order is already marked as delivered");

      $isDelivered = $this->getOrder()->update([
        'order_status' => 'Delivered',
        'is_delivered' => true
      ]);

      $order = $order->refresh();

      return $this->success("successfully marked order as delivered", $order);

    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
  }
  

  public function completeOrder($order_id) 
  {
    try {

      $this->setOrder($order_id);

      $order = $this->getOrder();
    
      $isNull = is_null($this->getOrder());
    
      if($isNull)
        throw new Exception ("Could not get order, please try again!");

      $isDelivered = $this->getOrder()->is_delivered;
      
      if(!$isDelivered)
        throw new Exception ("Order has to be delivered before completion");

      $isCompleted = $this->getOrder()->is_completed;
      
      if($isCompleted)
        throw new Exception ("Order is already marked as completed");

      $isCompleted = $this->getOrder()->update([
        'order_status' => 'Completed',
        'is_completed' => true
      ]);

      $order = $order->refresh();

      return $this->success("successfully marked order as completed", $order);

    } catch (Exception $e) {
      return $this->error($e->getMessage());
    }
    
  }

  //pending
  //recieved
  //processing
  //shipped
  //delivered
}
