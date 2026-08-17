<?php
require_once __DIR__ . '/auth.php';

const DEMO_TRANSFER_AUTHORIZATION_TTL_SECONDS = 600;
const DEMO_TRANSFER_MAX_AUTHORIZATION_ATTEMPTS = 5;

function transfer_csrf_token(): string
{
    if (!isset($_SESSION['transfer_csrf_token'])) {
        $_SESSION['transfer_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['transfer_csrf_token'];
}

function require_valid_transfer_csrf(): bool
{
    return isset($_POST['csrf_token'], $_SESSION['transfer_csrf_token'])
        && is_string($_POST['csrf_token'])
        && hash_equals($_SESSION['transfer_csrf_token'], $_POST['csrf_token']);
}

function demo_transfer_reference(): string
{
    return 'DTR-' . strtoupper(bin2hex(random_bytes(6)));
}

function valid_demo_transfer_amount(string $amount): ?string
{
    $amount = trim($amount);
    if (preg_match('/^\d+(?:\.\d{1,2})?$/', $amount) !== 1 || (float) $amount <= 0) {
        return null;
    }

    return number_format((float) $amount, 2, '.', '');
}

function normalise_demo_name(string $name): string
{
    return strtolower(trim(preg_replace('/\s+/', ' ', $name) ?? ''));
}
