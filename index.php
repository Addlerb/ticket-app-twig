<?php
require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . '/cache',
    'debug' => true,
]);

$session = json_decode($_COOKIE['ticketapp_session'] ?? '{}', true);

$route = $_SERVER['REQUEST_URI'];
if (preg_match('/\/auth/', $route)) {
    echo $twig->render('auth.twig', ['session' => $session]);
} elseif (preg_match('/\/dashboard/', $route)) {
    if (empty($session['token'])) {
        header('Location: /auth/login');
        exit;
    }
    echo $twig->render('dashboard.twig', ['stats' => getTicketStats()]);
} else {
    echo $twig->render('landing.twig', []);
}
?>
