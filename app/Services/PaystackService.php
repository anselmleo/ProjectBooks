<?php


namespace App\Services;

use Exception;

class PaystackService 
{
    
    public function initialize($email, $amount, $callback_url) 
    {
    $curl = curl_init();

    // $callback_url = url('/callback');

    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.paystack.co/transaction/initialize",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => json_encode([
        'amount' => $amount, // amount has to be in kobo
        'email' => $email, //email of the user
        'callback_url' => $callback_url //url to be redirected to after payment
      ]),
      CURLOPT_HTTPHEADER => [
        "authorization: Bearer sk_test_75ec48a18810f72333bd21fdba975f488bc61665", //replace this with your own test key
        "content-type: application/json",
        "cache-control: no-cache"
      ],
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    if ($err) {
      // there was an error contacting the Paystack API
      die('Curl returned error: ' . $err);
    }

    $tranx = json_decode($response, true);

    if (!$tranx['status']) {
      // there was an error from the API
      print_r('API returned error: ' . $tranx['message']);
    }

    return ['payload' => $tranx];
    
    // comment out this line if you want to redirect the user to the payment page
    // print_r($tranx);

    // redirect to page so User can pay
    // uncomment this line to allow the user redirect to the payment page
    // header('Location: ' . $tranx['data']['authorization_url']);
    }
    

    public function verifyPay($reference)
    {
      $curl = curl_init();

      // $reference = isset($_GET['reference']) ? $_GET['reference'] : '';
      if (!$reference) {
        throw new Exception("Can't find transaction reference");
      }

      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
          "accept: application/json",
          "authorization: Bearer sk_test_75ec48a18810f72333bd21fdba975f488bc61665",
          "cache-control: no-cache"
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if ($err) {
        // there was an error contacting the Paystack API
        die('Curl returned error: ' . $err);
      }

      $tranx = json_decode($response);

      if (!$tranx->status) {
        // there was an error from the API
        die('API returned error: ' . $tranx->message);
      }

      if ('success' == $tranx->data->status) {
        // transaction was successful...
        // please check other things like whether you already gave value for this ref
        // if the email matches the customer who owns the product etc
        // Give value
        return ['payment_verification_status' = true];
        // echo "<h2>Thank you for making a purchase. Your file has bee sent your email.</h2>";
      }
    }
}
