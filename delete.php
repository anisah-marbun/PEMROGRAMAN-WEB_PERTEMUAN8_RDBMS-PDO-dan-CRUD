<?php

require_once 'database.php';

$pdo =
Database::getInstance()->getConnection();

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID produk tidak ditemukan.");
}

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php");
exit;

?>