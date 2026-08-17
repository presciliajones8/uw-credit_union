<?php
session_start();
require_once __DIR__ . '/auth.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$localPath = __DIR__ . $uri;

if ($uri !== '/' && file_exists($localPath) && !is_dir($localPath)) {
    $extension = strtolower(pathinfo($localPath, PATHINFO_EXTENSION));
    $mimeTypes = [
        'css' => 'text/css; charset=UTF-8',
        'js' => 'application/javascript; charset=UTF-8',
        'html' => 'text/html; charset=UTF-8',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'svg' => 'image/svg+xml',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'json' => 'application/json; charset=UTF-8',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
    ];

    if (isset($mimeTypes[$extension])) {
        header('Content-Type: ' . $mimeTypes[$extension]);
    }

    readfile($localPath);
    return;
}

if ($uri === '/' || $uri === '') {
    if (is_logged_in()) {
        redirect_to('dash.php');
    }

    require __DIR__ . '/index.php';
    return;
}

if ($uri === '/login' || $uri === '/login/') {
    redirect_if_authenticated();
    require __DIR__ . '/login.php';
    return;
}

if ($uri === '/register' || $uri === '/register/') {
    redirect_if_authenticated();
    require __DIR__ . '/register.php';
    return;
}

if ($uri === '/logout' || $uri === '/logout/') {
    require __DIR__ . '/logout.php';
    return;
}

if ($uri === '/transactions' || $uri === '/transactions/') {
    require __DIR__ . '/transactions.php';
    return;
}

if ($uri === '/deposit' || $uri === '/deposit/') {
    require __DIR__ . '/deposit.php';
    return;
}

if ($uri === '/transfer-access' || $uri === '/transfer-access/') {
    require __DIR__ . '/transfer-access.php';
    return;
}

if ($uri === '/dash.php' || $uri === '/dash') {
    require __DIR__ . '/dash.php';
    return;
}

http_response_code(404);
echo 'Not Found';
