<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/login', 'POST', ['username' => 'adminnotfst', 'password' => 'password123']);
$response = $kernel->handle($request);
echo "Status Code: " . $response->getStatusCode() . "\n";
if ($response->isRedirect()) {
    echo "Redirect URL: " . $response->headers->get('Location') . "\n";
}
