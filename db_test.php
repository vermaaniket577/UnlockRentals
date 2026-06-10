<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', '');
    echo "Connected successfully to MySQL server.\n";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS unlock_rentals;");
    echo "Database created successfully.\n";
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage() . "\n";
}
