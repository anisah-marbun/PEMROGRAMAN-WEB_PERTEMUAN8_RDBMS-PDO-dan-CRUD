<?php

session_start();

require_once 'database.php';

$pdo = Database::getInstance()->getConnection();

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID produk tidak ditemukan.");
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);

$product = $stmt->fetch();

if (!$product) {
    die("Produk tidak ditemukan.");
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$suppliers = $pdo->query("SELECT * FROM suppliers ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "UPDATE products
            SET name = ?,
                category_id = ?,
                supplier_id = ?,
                price = ?,
                stock = ?
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $name,
        $category_id,
        $supplier_id,
        $price,
        $stock,
        $id
    ]);

    $_SESSION['success'] = 'Produk berhasil diperbarui.';

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Edit Produk</h1>

    <form method="POST">

        <p>
            <label>Nama Produk</label><br>
            <input
                type="text"
                name="name"
                value="<?= htmlspecialchars($product['name']) ?>"
                required
            >
        </p>

        <p>
            <label>Kategori</label><br>
            <select name="category_id" required>

                <?php foreach ($categories as $category): ?>

                    <option
                        value="<?= $category['id'] ?>"
                        <?= $category['id'] == $product['category_id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($category['name']) ?>
                    </option>

                <?php endforeach; ?>

            </select>
        </p>

        <p>
            <label>Supplier</label><br>
            <select name="supplier_id" required>

                <?php foreach ($suppliers as $supplier): ?>

                    <option
                        value="<?= $supplier['id'] ?>"
                        <?= $supplier['id'] == $product['supplier_id'] ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($supplier['name']) ?>
                    </option>

                <?php endforeach; ?>

            </select>
        </p>

        <p>
            <label>Harga</label><br>
            <input
                type="number"
                name="price"
                value="<?= $product['price'] ?>"
                min="0"
                required
            >
        </p>

        <p>
            <label>Stok</label><br>
            <input
                type="number"
                name="stock"
                value="<?= $product['stock'] ?>"
                min="0"
                required
            >
        </p>

        <button type="submit">Update Produk</button>

    </form>

    <br>

    <a href="index.php">Kembali ke Daftar Produk</a>

</body>

</html>