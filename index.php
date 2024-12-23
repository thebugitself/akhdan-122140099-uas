<?php
session_start();

// Jika pengguna sudah login, arahkan ke dashboard
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Toko Item Game</title>
  <link rel="stylesheet" href="./public/css/style.css" />
</head>

<body>
  <header class="header">
    <div class="container">
      <a href="index.php" class="logo">Toko Item Game</a>
      <p>Platform terbaik untuk membeli item dan kebutuhan gaming Anda</p>
    </div>
  </header>

  <nav class="navigation">
    <ul>
      <li><a href="./views/produk.php">Produk</a></li>
      <li><a href="./views/contact.php">Kontak</a></li>
      <?php if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']): ?>
        <li><a href="./views/register.php">Daftar</a></li>
        <li><a href="./views/login.php">Masuk</a></li>
      <?php else: ?>
        <li><a href="./views/dashboard.php">Dashboard</a></li>
        <li><a href="./controllers/logout.php" class="logout-button">Logout</a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <main class="main-content">
    <section class="hero">
      <h1>Selamat Datang di Toko Item Game</h1>
      <p>Jelajahi berbagai item eksklusif untuk game favorit Anda dengan harga terbaik!</p>
      <a href="./views/produk.php" class="cta-button">Lihat Produk</a>
    </section>

    <section class="products">
      <h2>Produk Unggulan</h2>
      <div class="product-list">
        <?php
        include './config/db_config.php';

        // Query untuk mengambil produk dari database
        $query = "SELECT id, name, price, image_url FROM products LIMIT 3"; // Batasi 3 produk untuk tampilan unggulan
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
          // Loop melalui produk dan tampilkan
          while ($row = $result->fetch_assoc()) {
            $id = htmlspecialchars($row['id']);
            $name = htmlspecialchars($row['name']);
            $price = number_format(htmlspecialchars($row['price']), 0, ',', '.'); // Format harga ke format Indonesia
            $imageUrl = htmlspecialchars($row['image_url']);
            echo "
          <div class='product'>
            <img src='$imageUrl' alt='$name' />
            <h3>$name</h3>
            <p>Harga: Rp $price</p>
            <a href='./views/detail.php?id=$id' class='btn'>Beli Sekarang</a>
          </div>
        ";
          }
        } else {
          echo "<p>Tidak ada produk untuk ditampilkan.</p>";
        }

        // Tutup koneksi database
        $conn->close();
        ?>
      </div>
    </section>

    <section class="features">
      <h2>Kenapa Memilih Kami?</h2>
      <ul>
        <li>Harga terjangkau dengan berbagai promo menarik.</li>
        <li>Proses transaksi cepat dan aman.</li>
        <li>Dukungan pelanggan yang siap membantu 24/7.</li>
      </ul>
    </section>

    <section class="cta">
      <h2>Siap Meningkatkan Pengalaman Bermain Anda?</h2>
      <p>Daftar sekarang dan nikmati berbagai keuntungan di Toko Item Game!</p>
      <a href="./views/register.php" class="cta-button">Daftar Sekarang</a>
    </section>
  </main>

  <footer class="footer">
    <p>&copy; 2024 Toko Item Game.</p>
  </footer>
</body>

</html>