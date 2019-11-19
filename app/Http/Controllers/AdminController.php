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
    $this->middleware('auth:api');

    $this->middleware('role:admin', ['only' => [
      'getAllUsers', 'getAllOrders', 'payOrder', 'processOrder', 
      'receiveOrder', 'shipOrder', 'deliverOrder', 'completeOrder'
    ]]);

    $this->adminRepository = $adminRepository;
  }

  

  }
