<?php
$path = __DIR__ . "/db/banco.sqlite";
$pdo = new PDO("sqlite:" . $path);

try {
    $pdo = new PDO("sqlite:" . $path);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro banco: " . $e->getMessage());
}
