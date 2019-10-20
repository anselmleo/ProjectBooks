<?php


namespace App\Repositories\Concretes;

use App\Repositories\Contracts\IPaystackRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use App\Services\PaystackService;


class PaystackRepository implements IPaystackRepository
{
  private $paystackService;
  


  /**
   * @return mixed
   */
  public function initialize(array $params)
  {
    [
      'email' => $email,
      'amount' => $amount,
      'callback_url' => $callback_url
    ] = $params;

    $payInitialize = $this->paystackService->initialize($email, $amount, $callback_url);
    return [ 'payload' => $payInitialize ];
  }
}
