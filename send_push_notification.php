<?php
ini_set('display_errors', 'Off');
error_reporting(E_ALL);
require __DIR__ . '/vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// here I'll get the subscription endpoint in the POST parameters
// but in reality, you'll get this information in your database
// because you already stored it (cf. push_subscription.php)
$data = file_get_contents('php://input');
$subscription = Subscription::create((array)json_decode($data, true));

$auth = array(
    'VAPID' => array(
        'subject' => 'notice-test',
        'publicKey' => file_get_contents(__DIR__ . '/keys/public_key.txt'), // don't forget that your public key also lives in app.js
        'privateKey' => file_get_contents(__DIR__ . '/keys/private_key.txt'), // in the real world, this would be in a secret file
    ),
);

$webPush = new WebPush($auth);

$report = $webPush->sendOneNotification(
    $subscription,
    json_encode([
        'title' => 'notice',
        'body' => '果然是李贺的问题! 👋',
        'icon' => 'https://www.python.org/static/img/python-logo.png'
    ])
);

// handle eventual errors here, and remove the subscription from your server if it is expired
$endpoint = $report->getRequest()->getUri()->__toString();

if ($report->isSuccess()) {
//    file_put_contents(__DIR__.'/log2.txt', "[v] Message sent successfully for subscription {$endpoint}.");

    echo "[v] Message sent successfully for subscription {$endpoint}.";
} else {
//    file_put_contents(__DIR__.'/log2.txt', "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}");

    echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
}
