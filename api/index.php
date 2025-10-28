<?php
// api/index.php - Railway entrypoint for Symfony
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;

$request = Request::createFromGlobals();
$kernel = new \App\Kernel('prod', false);
$kernel->boot();

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);
