<?php
include '../config/db_config.php';

header('Content-Type: application/json');

// Endpoint untuk mendapatkan semua produk
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'fetch') {
    $query = "SELECT * FROM products";
    $result = $conn->query($query);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

// Endpoint untuk mendapatkan produk berdasarkan ID
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $_GET['action'] === 'fetchById') {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode(null); // Produk tidak ditemukan
    }

    $stmt->close();
}

// Endpoint untuk menambahkan produk baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'create') {
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);
    $image_url = htmlspecialchars($_POST['image_url']);
    $stock = htmlspecialchars($_POST['stock']);

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, stock) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $stock);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Produk berhasil ditambahkan"]);
    } else {
        echo json_encode(["error" => "Gagal menambahkan produk"]);
    }
    $stmt->close();
}

// Endpoint untuk memperbarui produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'update') {
    $id = intval($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $price = htmlspecialchars($_POST['price']);
    $image_url = htmlspecialchars($_POST['image_url']);
    $stock = htmlspecialchars($_POST['stock']);

    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, stock = ? WHERE id = ?");
    $stmt->bind_param("ssdssi", $name, $description, $price, $image_url, $stock, $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Produk berhasil diupdate"]);
    } else {
        echo json_encode(["error" => "Gagal mengupdate produk"]);
    }
    $stmt->close();
}

// Endpoint untuk menghapus produk
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_GET['action'] === 'delete') {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Produk berhasil dihapus"]);
    } else {
        echo json_encode(["error" => "Gagal menghapus produk"]);
    }
    $stmt->close();
}

$conn->close();
?>