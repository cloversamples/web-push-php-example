<?php
$data = file_get_contents('php://input');
file_put_contents(__DIR__.'/data.json', $data);
$subscription = json_decode($data, true);

if (!isset($subscription['endpoint'])) {
    echo 'Error: not a subscription';
    return;
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // create a new subscription entry in your database (endpoint is unique)
        break;
    case 'PUT':
        // update the key and token of subscription corresponding to the endpoint
        break;
    case 'DELETE':
        // delete the subscription corresponding to the endpoint
        break;
    default:
        echo "Error: method not handled";
        return;
}
