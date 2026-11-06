<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);


// Case 1: Accessed via IP:8000
$req1 = Illuminate\Http\Request::create('http://192.168.1.50:8000/login', 'GET');
$res1 = $kernel->handle($req1);
preg_match_all('/<link[^>]+>/', $res1->getContent(), $matches1);
echo "=== REQUEST TO 192.168.1.50:8000 ===\n";
print_r($matches1[0]);

// Case 2: Accessed via XAMPP Apache: http://192.168.1.50/HALOBK/public/login
$server = [
    'SCRIPT_NAME' => '/HALOBK/public/index.php',
    'SCRIPT_FILENAME' => 'C:/xampp/htdocs/HALOBK/public/index.php',
    'PHP_SELF' => '/HALOBK/public/index.php',
    'HTTP_HOST' => '192.168.1.50',
    'REQUEST_URI' => '/HALOBK/public/login',
];
$req2 = Illuminate\Http\Request::create('http://192.168.1.50/HALOBK/public/login', 'GET', [], [], [], $server);
$res2 = $kernel->handle($req2);
echo "Status 2: " . $res2->getStatusCode() . "\n";
$req3 = Illuminate\Http\Request::create('http://192.168.1.50/HALOBK/public/', 'GET', [], [], [], $server);
$res3 = $kernel->handle($req3);
echo "Status /: " . $res3->getStatusCode() . "\n";
echo "Location: " . $res3->headers->get('Location') . "\n";
