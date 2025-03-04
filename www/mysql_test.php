<?php

$host = 'mysql5';
$db   = 'test';
$user = 'root';
$pass = '123456';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Successfully connected to MySQL!\n";
} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
} 