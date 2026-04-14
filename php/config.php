<?php
session_start();

// 👇 CONFIGURAÇÃO EXATA PARA SEU MySQL
$host = 'localhost:3307';     // ← Porta 3307
$dbname = 'skillswap';
$username = 'root';
$password = '123456';         // ← Sua senha

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão OK!"; // Remove depois
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}
?>