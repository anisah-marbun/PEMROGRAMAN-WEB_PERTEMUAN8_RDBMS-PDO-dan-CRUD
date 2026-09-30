<?php

session_start();

require_once 'database.php';

$pdo = Database::getInstance()->getConnection();

$sql = "SELECT 
            p.id,
            p.name,
            p.price,
            p.stock,
            c.name AS category,
            s.name AS supplier
        FROM products p
        JOIN categories c ON p.category_id = c.id
        JOIN suppliers s ON p.supplier_id = s.id
        ORDER BY p.name";

$stmt = $pdo->query($sql);
$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Barang</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Daftar Produk</h1>
    <?php if (isset($_SESSION['success'])): ?>

    <p style="color: green;">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </p>

    <?php unset($_SESSION['success']); ?>

    <?php endif; ?>

    <a href="create.php">+ Tambah Produk</a>

    <br><br>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Supplier</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>

        <?php foreach ($products as $p): ?>

            <tr>
                <td><?= $p['id'] ?></td>

                <td>
                    <?= htmlspecialchars($p['name']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($p['category']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($p['supplier']) ?>
                </td>

                <td>
                    Rp <?= number_format($p['price'], 0, ',', '.') ?>
                </td>

                <td>
                    <?= $p['stock'] ?>
                </td>

                <td>
                    <a href="edit.php?id=<?= $p['id'] ?>">
                        Edit
                    </a>

                    |

                    <a
                        href="delete.php?id=<?= $p['id'] ?>"
                        onclick="return confirm('Apakah kamu yakin ingin menghapus produk ini?');"
                    >
                        Hapus
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

    </table>

</body>
</html>