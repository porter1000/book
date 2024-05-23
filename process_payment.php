<?php
require 'vendor/autoload.php';
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

$token  = $_POST['stripeToken'];
$email  = $_POST['stripeEmail'];
$watermarkedImagePath = $_POST['watermarkedImagePath'];

$charge = \Stripe\Charge::create([
    'amount' => 5000, // $50
    'currency' => 'usd',
    'description' => 'KDP Book Cover',
    'source' => $token,
    'receipt_email' => $email,
]);
$img = imagecreatefromjpeg($watermarkedImagePath);

$finalImagePath = str_replace('_watermarked', '_final', $watermarkedImagePath);
imagejpeg($img, $finalImagePath);

imagedestroy($img);

header('Location: success.php?image=' . urlencode($finalImagePath));
?>
