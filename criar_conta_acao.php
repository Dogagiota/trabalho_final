<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$path = __DIR__ . '/db/clientes.sqlite';
try {
    $pdo = new PDO('sqlite:/var/www/trabalho_final/db/clientes.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $data = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO clientes (nome_cliente, email, senha, telefone, data_cadastro) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $email, $senha, $telefone, $data]);

    header("Location: login.php?sucesso=1");
    exit;

} catch (PDOException $e) {
    die("Erro no banco: " . $e->getMessage());
}
