<?php

use infobip\Configuration;
use Infobip\Api\SmsApi;
use infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require __DIR__ . "vendor/autoload.php";

require_once 'HTTP/Request2.php';

// Replace these placeholders with your actual values
$pinId = 'your_pin_id'; // The ID of the PIN you generated
$phoneNumber = 'phone_number'; // The phone number where you want to send the PIN code
$appKey = '5cc9b9a035efb59dd420518153f62e97-ee0331c0-821e-47de-be60-f0373e633079'; // Your Infobip application key

// URL for verifying the PIN
$verifyUrl = "https://w1ez6r.api.infobip.com/2fa/2/pin/{$pinId}/verify";

// Generate a random 4-digit PIN code
$pinCode = rand(1000, 9999);

// Prepare the JSON payload with the PIN code and phone number
$requestBody = json_encode([
    'pin' => $pinCode,
    'phoneNumber' => $phoneNumber,
]);

$request = new HTTP_Request2();
$request->setUrl($verifyUrl);
$request->setMethod(HTTP_Request2::METHOD_POST);
$request->setConfig(['follow_redirects' => true]);
$request->setHeader([
    'Authorization' => "App {$appKey}",
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
]);
$request->setBody($requestBody);

try {
    $response = $request->send();
    if ($response->getStatus() == 200) {
        echo 'PIN sent successfully.';
        // You can handle the response from Infobip here if needed
    } else {
        echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' . $response->getReasonPhrase();
    }
} catch (HTTP_Request2_Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>