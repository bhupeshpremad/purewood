<?php
$host = 'localhost';
$db   = 'purewood';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage());
}

if (!class_exists('AppConfig')) {
    class AppConfig {
        public static function baseUrl() {
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
            $host = $_SERVER['HTTP_HOST'];
            $basePath = ''; 
            return $protocol . '://' . $host . $basePath;
        }
    }
}
?>
