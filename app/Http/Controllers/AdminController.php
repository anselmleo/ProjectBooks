<?php


namespace App\Http\Controllers;

use App\Repositories\Contracts\IAdminRepository;
use App\Models\Order;
use App\Models\User;
use Exception;

class AdminController extends Controller
{
  /**
   * @var IAdminRepository
   */
  private $adminRepository;

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


  /**
   * Get all orders
   * @return Orders
   */
  public function getAllOrders() 
  {
    try {
      $allOrders = Order::all();
      return response()->json([
        'status' => true,
        'payload' => $allOrders
      ]);
    } catch (Exception $e) {
      return response()->json([
        'error' => $e
      ]);
    }
  }

  /**
   * Get all users
   * @return Users
   */
  public function getAllUsers()
  {
    try {
      $allUsers = User::all();
      return response()->json([
        'status' => true,
        'payload' => $allUsers
      ]);
    } catch (Exception $e) {
      return response()->json([
        'error' => $e
      ]);
    }
  }

  public function processOrder($order_id) 
  {
    if($is_proccessing)
      $is_proccessing = true;
  }
}
