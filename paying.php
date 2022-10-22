<?php


if (isset($_POST['pay'])) {
  $curl = curl_init();
  $name = $_POST['name'];
  $customer_email = $_POST['email'];
  $currency = $_POST['NGN'];
  $amount = $_POST['amount']; 
  $txref = "rave" . uniqid();


  // ensure you generate unique references per transaction.
// get your public key from the dashboard.
$PBFPubKey = "FLWPUBK-afa7af7af764cc69291f31c0205c090359-x"; 
$redirect_url = "Okiksha\status.php"; // Set your own redirect URL


curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/hosted/pay",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
    'amount'=>$amount,
    'customer_email'=>$customer_email,
    'currency'=>$currency,
    'txref'=>$txref,
    'PBFPubKey'=>$PBFPubKey,
    'redirect_url'=>$redirect_url,
  ]),
  CURLOPT_HTTPHEADER => [
    "content-type: application/json",
    "cache-control: no-cache"
  ],
));

$response = curl_exec($curl);
$err = curl_error($curl);

if($err){
  // there was an error contacting the rave API
  die('Curl returned error: ' . $err);
}

$transaction = json_decode($response);

if(!$transaction->data && !$transaction->data->link){
  // there was an error from the API
  print_r('API returned error: ' . $transaction->message);
}

// redirect to page so User can pay

header('Location: ' . $transaction->data->link);
}