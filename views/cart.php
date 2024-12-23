<?php
session_start();

// Ambil keranjang dari session
$sessionCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Keranjang - Toko Item Game</title>
    <link rel="stylesheet" href="../public/css/style.css" />
</head>

<body>
    <header class="header">
        <div class="container">
            <a href="../index.php" class="logo">Toko Item Game</a>
            <p>Platform terbaik untuk membeli item dan kebutuhan gaming Anda</p>
        </div>
    </header>

    <main class="main-content">
        <section class="cart">
            <h1>Keranjang Belanja</h1>

            <!-- Tabel Keranjang dari Session -->
            <h2>Keranjang Anda (Dari Sesi)</h2>
            <?php if (empty($sessionCart)): ?>
                <p>Keranjang di sesi Anda kosong.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $totalSession = 0;
                        foreach ($sessionCart as $id => $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $totalSession += $subtotal;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td>Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <h2>Total Sesi: Rp <?php echo number_format($totalSession, 0, ',', '.'); ?></h2>
            <?php endif; ?>

            <!-- Tabel Keranjang dari LocalStorage -->
            <h2>Keranjang Anda (Dari LocalStorage)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody id="localStorageCart">
                    <!-- Data akan dimuat dengan JavaScript -->
                </tbody>
            </table>
            <h2>Total LocalStorage: <span id="localTotal">Rp 0</span></h2>

            <a href="../index.php" class="btn">Lanjutkan Belanja</a>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 Toko Item Game. Semua hak dilindungi.</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Ambil data keranjang dari localStorage
            const cart = JSON.parse(localStorage.getItem('cart')) || {};
            const tableBody = document.getElementById('localStorageCart');
            let total = 0;

            Object.entries(cart).forEach(([productId, product]) => {
                const row = document.createElement('tr');
                const subtotal = product.price * product.quantity;
                total += subtotal;

                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>Rp ${parseFloat(product.price).toLocaleString('id-ID')}</td>
                    <td>${product.quantity}</td>
                    <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
                `;
                tableBody.appendChild(row);
            });

            // Tampilkan total harga dari localStorage
            document.getElementById('localTotal').textContent = `Rp ${total.toLocaleString('id-ID')}`;
        });
    </script>
</body>

</html>