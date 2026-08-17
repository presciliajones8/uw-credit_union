<?php
function app_base_path(): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
    $scriptDir = dirname($scriptName);

    if ($scriptDir === '/' || $scriptDir === '\\' || $scriptDir === '.') {
        return '';
    }

    return rtrim($scriptDir, '/');
}

function app_url(string $path = ''): string
{
    $base = app_base_path();
    $path = ltrim($path, '/');

    if ($path === '') {
        return $base === '' ? '/' : $base . '/';
    }

    return $base === '' ? '/' . $path : $base . '/' . $path;
}

function redirect_to(string $path): void
{
    $url = app_url($path);
    header('Location: ' . $url);
    exit;
}

function arffib_db(): mysqli
{
    static $connection = null;

    if ($connection === null) {
        $host = 'localhost';
        $dbUser = 'root';
        $dbPassword = '';
        $dbname = 'bank_system';

        $connection = new mysqli($host, $dbUser, $dbPassword, $dbname);
        if ($connection->connect_error) {
            throw new RuntimeException('Database connection failed: ' . $connection->connect_error);
        }
    }

    return $connection;
}

function session_started(): bool
{
    return session_status() === PHP_SESSION_ACTIVE;
}

function is_logged_in(): bool
{
    if (!session_started()) {
        session_start();
    }

    $userId = $_SESSION['user_id'] ?? null;
    return is_numeric($userId) && (int) $userId > 0;
}

function current_user_id(): ?int
{
    if (!is_logged_in()) {
        return null;
    }

    $userId = $_SESSION['user_id'] ?? null;
    return is_numeric($userId) ? (int) $userId : null;
}

function require_auth(): void
{
    if (!is_logged_in()) {
        redirect_to('login.php');
    }
}

function redirect_if_authenticated(string $destination = 'dash.php'): void
{
    if (is_logged_in()) {
        redirect_to($destination);
    }
}

function verify_password(string $password, ?string $storedHash): bool
{
    if ($storedHash === null || $storedHash === '') {
        return false;
    }

    if (password_verify($password, $storedHash)) {
        return true;
    }

    return hash_equals((string) $storedHash, (string) $password);
}

function login_user(string $email, string $password): bool
{
    if (!session_started()) {
        session_start();
    }

    $email = trim($email);
    $password = (string) $password;

    if ($email === '' || $password === '') {
        return false;
    }

    $conn = arffib_db();
    $stmt = $conn->prepare('SELECT id, fullName, email, password FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result || $result->num_rows !== 1) {
        $stmt->close();
        return false;
    }

    $user = $result->fetch_assoc();
    $stmt->close();

    if (!verify_password($password, $user['password'] ?? null)) {
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['fullName'];

    return true;
}

function register_user(string $firstName, string $lastName, string $email, string $phone, string $password): bool
{
    if (!session_started()) {
        session_start();
    }

    $firstName = trim($firstName);
    $lastName = trim($lastName);
    $email = trim($email);
    $phone = trim($phone);
    $password = (string) $password;

    if ($firstName === '' || $lastName === '' || $email === '' || $phone === '' || $password === '') {
        return false;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    if (strlen($password) < 8) {
        return false;
    }

    $conn = arffib_db();
    $existing = $conn->prepare('SELECT id FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1');
    $existing->bind_param('s', $email);
    $existing->execute();
    $existingResult = $existing->get_result();

    if ($existingResult && $existingResult->num_rows > 0) {
        $existing->close();
        return false;
    }
    $existing->close();

    $fullName = trim($firstName . ' ' . $lastName);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $accessCode = strtoupper(bin2hex(random_bytes(3)));

    $stmt = $conn->prepare('INSERT INTO users (fullName, phone, email, password, balance, accountType, branch, idDocument, idDocument1, access_code, created_at) VALUES (?, ?, ?, ?, 0.00, ?, ?, ?, ?, ?, NOW())');
    $accountType = 'Savings';
    $branch = 'Main Branch';
    $idDocument = '';
    $idDocument1 = '';

    $stmt->bind_param('sssssssss', $fullName, $phone, $email, $hashedPassword, $accountType, $branch, $idDocument, $idDocument1, $accessCode);
    $saved = $stmt->execute();
    $stmt->close();

    if (!$saved) {
        return false;
    }

    $userId = $conn->insert_id;
    $_SESSION['user_id'] = (int) $userId;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $fullName;
    session_regenerate_id(true);

    return true;
}
