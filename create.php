<?php

session_start();
require_once 'database.php';

$pdo = Database::getInstance()->getConnection();

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$suppliers = $pdo->query("SELECT * FROM suppliers ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $supplier_id = $_POST['supplier_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $sql = "INSERT INTO products
            (name, category_id, supplier_id, price, stock)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        $name,
        $category_id,
        $supplier_id,
        $price,
        $stock
    ]);
    
    $_SESSION['success'] = 'produk berhasil ditambahkan.';

    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
</head>

<body>

    <h1>Tambah Produk</h1>

    <form method="POST">

        <p>
            <label>Nama Produk</label><br>
            <input type="text" name="name" required>
        </p>

        <p>
            <label>Kategori</label><br>
            <select name="category_id" required>
                <option value="">-- Pilih Kategori --</option>

                <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </p>

        <p>
            <label>Supplier</label><br>
            <select name="supplier_id" required>
                <option value="">-- Pilih Supplier --</option>

                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?= $supplier['id'] ?>">
                        <?= htmlspecialchars($supplier['name']) ?>
                    </option>
                <?php endforeach; ?>

            </select>
        </p>

        <p>
            <label>Harga</label><br>
            <input type="number" name="price" min="0" required>
        </p>

        <p>
            <label>Stok</label><br>
            <input type="number" name="stock" min="0" required>
        </p>

        <button type="submit">Simpan</button>

    </form>

    <br>

    <a href="index.php">Kembali ke Daftar Produk</a>

</body>
</html>