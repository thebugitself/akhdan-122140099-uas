<?php
session_start();

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
  header("Location: dashboard.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Toko Item Game</title>
  <link rel="stylesheet" href="../public/css/style.css" />
  <script src="../public/js/login.js" defer></script>
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
    <section class="form-section">
      <h1>Login Akun</h1>

      <?php if (isset($_GET['error']) && $_GET['error'] === 'login_required'): ?>
        <p class="error">Anda harus login untuk melanjutkan.</p>
      <?php endif; ?>

      <form id="loginForm" method="POST" action="../process/process_login.php" class="form-container">
        <!-- Email -->
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required />
        <span id="emailError" class="error"></span>

        <!-- Password -->
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required />
        <span id="passwordError" class="error"></span>

        <!-- Submit Button -->
        <button type="submit" class="cta-button">Masuk</button>
      </form>
    </section>
  </main>

  <footer class="footer">
    <p>&copy; 2024 Toko Item Game.</p>
  </footer>
</body>

</html>