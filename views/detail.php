<?php
session_start();
include '../config/db_config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Produk tidak ditemukan.");
    }

    $product = $result->fetch_assoc();
    $stmt->close();
} else {
    die("ID produk tidak ditemukan.");
}

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Fungsi untuk menambah produk ke keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    // Periksa apakah pengguna telah login
    if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
        header("Location: ../views/login.php?error=login_required");
        exit;
    }

    $productId = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
            ];
        }
        header("Location: cart.php");
        exit;
    } else {
        $error = "Kuantitas harus lebih dari 0.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Produk - Toko Item Game</title>
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
        <section class="product-detail">
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>"
                alt="<?php echo htmlspecialchars($product['name']); ?>" />
            <p><?php echo htmlspecialchars($product['description']); ?></p>
            <p>Harga: Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
            <p>Stok: <?php echo htmlspecialchars($product['stock']); ?></p>

            <h2>Menggunakan Form</h2>
            <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <label for="quantity">Jumlah:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1"
                    max="<?php echo $product['stock']; ?>" required>
                <?php if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']): ?>
                    <button type="submit" name="add_to_cart" class="btn">Tambah ke Keranjang</button>
                <?php else: ?>
                    <button type="button" class="btn" onclick="window.location.href='../views/login.php';">
                        Login untuk Menambah ke Keranjang
                    </button>
                <?php endif; ?>
            </form>

            <?php if (isset($error)): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <h2>Menggunakan LocalStorage dan Session Storage</h2>
            <form id="addToCartForm">
                <input type="hidden" id="productId" value="<?php echo $product['id']; ?>">
                <label for="quantity">Jumlah:</label>
                <input type="number" id="quantityInputLocal" value="1" min="1" max="<?php echo $product['stock']; ?>"
                    required>
                <button type="button" class="btn" onclick="addToCart()">Tambah ke Keranjang</button>
            </form>


            <a href="./produk.php" class="btn">Kembali ke Produk</a>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; 2024 Toko Item Game.</p>
    </footer>

    <script>
        function addToCart() {
            // Ambil data produk dari elemen HTML
            const productId = document.getElementById('productId').value;
            // dapatkan data quantity dari elemen HTML
            const quantity = parseInt(document.getElementById('quantityInputLocal').value);
            const productName = "<?php echo htmlspecialchars($product['name']); ?>";
            const productPrice = parseFloat("<?php echo floatval($product['price']); ?>");

            // Validasi jumlah
            if (isNaN(quantity) || quantity <= 0) {
                alert('Jumlah harus berupa angka dan lebih dari 0.');
                return;
            }

            // Ambil keranjang dari localStorage
            let cart = JSON.parse(localStorage.getItem('cart')) || {};

            // Tambahkan atau perbarui produk di keranjang
            if (cart[productId]) {
                cart[productId].quantity += quantity;
            } else {
                cart[productId] = {
                    name: productName,
                    price: productPrice,
                    quantity: quantity
                };
            }

            // Simpan kembali ke localStorage
            localStorage.setItem('cart', JSON.stringify(cart));

            alert('Produk berhasil ditambahkan ke keranjang localStorage!');
            window.location.href = '../views/cart.php';
        }
    </script>

</body>

</html>