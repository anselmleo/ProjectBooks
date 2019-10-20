<?php


namespace App\Http\Controllers;


use App\Utils\Rules;
use Exception;
use App\Services\Paystack;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Mockery\Matcher\Type;


class PaystackController extends Controller
{
  private $paystackService;


  public function __construct(Paystack $paystackService)
  {
    $this->paystackService = $paystackService;
  }


  public function initialize()
  {
    $payload = request()->all();

    $validator = Validator::make($payload, Rules::get('PAYSTACK_INITIALIZE'));

    If($validator->fails()) {
        return $this->validationErrors($validator->getMessage()->all());
    }

    $reference = $this->paystackService->genTranxRef();
    $email = request()->email;
    $amount = request()->amount;

    try {
      $payInitialize = $this->paystackService->initialize($reference, $amount, $email);
      return $this->withData($payInitialize);

    } catch (Exception $e) {
      return $this->error($e->getMessageBag());
    }
  }
  

  public function verifyPay()
  {
    $payload = request()->all();

    $validator = Validator::make($payload, Rules::get('PAYSTACK_VERIFY'));

    If($validator->fails()) {
      return $this->validationErrors($validator->getMessageBag()->all());
    }

    try {
      $verifyPay = $this->paystackService->verify(request()->reference);
      return $this->withData($verifyPay);
      // return redirect($callback_url);
    } catch (Exception $e) {
      return $this->error($e->getMessage());
      // report($e);
      // return redirect('https://fotomi.now.sh?error=' . $e->getMessage());
    }
  }
}