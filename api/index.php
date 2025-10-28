<?php
// api/index.php - Vercel entrypoint for Symfony/Twig app
require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Create the Symfony request from Vercel's environment
$request = Request::createFromGlobals();

// Boot the Symfony kernel
$kernel = new \App\Kernel('prod', false);
$kernel->boot();

// Handle the request
$response = $kernel->handle($request);

// Send the response
$response->send();

// Terminate the kernel
$kernel->terminate($request, $response);
