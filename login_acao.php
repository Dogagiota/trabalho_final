<?php
require "conexao.php";
session_start();

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$sql = "SELECT * FROM clientes WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->execute([':email' => $email]);

$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($cliente && password_verify($senha, $cliente['senha'])) {
    $_SESSION['id_cliente'] = $cliente['id_cliente'];
    $_SESSION['nome'] = $cliente['nome_cliente'];

    header("Location: ia.php");
    exit;
} else {
    header("Location: login.php?erro=1");
    exit;
}
