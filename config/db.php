<?php
$env = parse_ini_file(__DIR__ . '/../.env');

$host = $env['DB_HOST'] ?? 'localhost';
$db   = $env['DB_NAME'] ?? '';
$user = $env['DB_USER'] ?? '';
$pass = $env['DB_PASS'] ?? '';


try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}

$env = parse_ini_file(__DIR__ . '/../.env');

define('EMAILJS_PUBLIC_KEY', $env['EMAILJS_PUBLIC_KEY'] ?? '');
define('EMAILJS_SERVICE_ID', $env['EMAILJS_SERVICE_ID'] ?? '');
define('EMAILJS_TEMPLATE_ID', $env['EMAILJS_TEMPLATE_ID'] ?? '');
