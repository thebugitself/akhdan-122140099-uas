<?php
session_start();
include '../config/db_config.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Produk - Toko Item Game</title>
    <link rel="stylesheet" href="../public/css/style.css" />
</head>

<body>
    <header class="header">
        <div class="container">
            <a href="../index.php" class="logo">Toko Item Game</a>
            <p>Platform terbaik untuk membeli item dan kebutuhan gaming Anda</p>
        </div>
    </header>

    <nav class="navigation">
        <ul>
            <li><a href="produk.php">Produk</a></li>
            <li><a href="contact.php">Kontak</a></li>
            <?php if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']): ?>
                <li><a href="register.php">Daftar</a></li>
                <li><a href="login.php">Masuk</a></li>
            <?php else: ?>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="../controllers/logout.php" class="logout-button">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <main class="main-content">
        <section class="products">
            <h1>Semua Produk</h1>
            <div class="product-list">
                <?php
                $query = "SELECT id, name, price, image_url FROM products";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $id = htmlspecialchars($row['id']);
                        $name = htmlspecialchars($row['name']);
                        $price = number_format(htmlspecialchars($row['price']), 0, ',', '.');
                        $imageUrl = htmlspecialchars($row['image_url']);
                        echo "
              <div class='product'>
                <img src='$imageUrl' alt='$name' />
                <h3>$name</h3>
                <p>Harga: Rp $price</p>
                <a href='./detail.php?id=$id' class='btn'>Lihat Detail</a>
              </div>
            ";
                    }
                } else {
                    echo "<p>Tidak ada produk untuk ditampilkan.</p>";
                }

                $conn->close();
                ?>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 Toko Item Game. Semua hak dilindungi.</p>
    </footer>
</body>

</html>