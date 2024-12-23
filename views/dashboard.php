<?php
session_start();
if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
  header("Location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self'; style-src 'self';">
  <title>Dashboard Produk</title>
  <link rel="stylesheet" href="../public/css/style.css" />
  <script src="../public/js/product.js" defer></script>
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
        <li><a href="storage-management.html">Cookie, LocalStorage, SessionStorage</a></li>
        <li><a href="../controllers/logout.php" class="logout-button">Logout</a></li>
      <?php endif; ?>
    </ul>
  </nav>


  <p>Welcome, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong>!</p>
  <p>IP Address: <strong><?php echo htmlspecialchars($_SESSION['ip_address']); ?></strong></p>
  <p>Browser: <strong><?php echo htmlspecialchars($_SESSION['browser']); ?></strong></p>

  <h1>Data Produk</h1>

  <form id="productForm" method="POST">
    <label>
      Nama Produk:
      <input type="text" name="name" id="name" placeholder="Masukkan nama produk" required />
    </label>
    <label>
      Deskripsi:
      <textarea name="description" id="description" placeholder="Deskripsi produk" required></textarea>
    </label>
    <label>
      Harga:
      <input type="number" name="price" id="price" step="0.01" placeholder="Harga produk" required />
    </label>
    <label>
      URL Gambar:
      <input type="text" name="image_url" id="image_url" placeholder="URL gambar produk" required />
    </label>
    <label>
      Stok:
      <input type="number" name="stock" id="stock" placeholder="Stok produk" required />
    </label>
    <button type="submit" id="submitButton" class="add-product-btn" disabled>Tambah Produk</button>
  </form>

  <h2>Daftar Produk</h2>
  <table id="productTable">
    <thead>
      <tr>
        <th>Nama Produk</th>
        <th>Deskripsi</th>
        <th>Harga</th>
        <th>URL Gambar</th>
        <th>Stok</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <!-- Data akan dimuat dengan JavaScript -->
    </tbody>
  </table>
</body>

</html>