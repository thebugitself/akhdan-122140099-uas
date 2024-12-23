<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kontak - Toko Item Game</title>
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
        <section class="contact-form">
            <h1>Hubungi Kami</h1>
            <p>Jika Anda memiliki pertanyaan atau masukan, jangan ragu untuk menghubungi kami melalui formulir di bawah
                ini.</p>
            <form action="#" method="POST">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" required />

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required />

                <label for="message">Pesan:</label>
                <textarea id="message" name="message" placeholder="Tulis pesan Anda" required></textarea>

                <button type="submit">Kirim</button>
            </form>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 Toko Item Game. Semua hak dilindungi.</p>
    </footer>
</body>

</html>