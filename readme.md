Berikut adalah struktur file `README.md` yang menjelaskan isi dari repository Anda dengan konteks yang diberikan:

---

# Toko Item Game - thebugitself

## **Deskripsi Proyek**

Toko Item Game thebugitself adalah aplikasi berbasis web yang dirancang untuk memfasilitasi pembelian item game secara online. Aplikasi ini menyediakan berbagai fitur, termasuk autentikasi pengguna, manajemen produk, keranjang belanja berbasis session dan LocalStorage, serta pengelolaan penyimpanan browser seperti Cookie, LocalStorage, dan SessionStorage.

---

## **Bagian 1: Client-side Programming (30%)**

### **1.1 Manipulasi DOM dengan JavaScript (15%)**

#### **Deskripsi**

- Aplikasi ini memanfaatkan manipulasi DOM untuk menampilkan dan mengelola data pengguna serta produk langsung di browser.
- Data yang diterima dari server (melalui AJAX atau fetch API) ditampilkan dalam tabel atau elemen HTML lainnya.

#### **Contoh Implementasi**

- File: `public/js/product.js`
  - **Form input dengan minimal 4 elemen**:
    ```html
    <form id="productForm" method="POST">
      <label
        >Nama Produk: <input type="text" name="name" id="name" required
      /></label>
      <label
        >Deskripsi:
        <textarea name="description" id="description" required></textarea>
      </label>
      <label
        >Harga: <input type="number" name="price" id="price" required
      /></label>
      <label
        >URL Gambar:
        <input type="text" name="image_url" id="image_url" required
      /></label>
      <label
        >Stok: <input type="number" name="stock" id="stock" required
      /></label>
      <button type="submit" id="submitButton">Tambah Produk</button>
    </form>
    ```
  - **Menampilkan data dari server ke dalam tabel HTML**:
    ```javascript
    fetch("../controllers/products_controller.php?action=fetch")
      .then((response) => response.json())
      .then((data) => {
        const productTable = document
          .getElementById("productTable")
          .querySelector("tbody");
        data.forEach((product) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>${product.name}</td>
            <td>${product.description}</td>
            <td>Rp ${parseFloat(product.price).toLocaleString("id-ID")}</td>
            <td><a href="${
              product.image_url
            }" target="_blank">Lihat Gambar</a></td>
            <td>${product.stock}</td>
          `;
          productTable.appendChild(row);
        });
      });
    ```

---

### **1.2 Event Handling (15%)**

#### **Deskripsi**

- Aplikasi ini menggunakan event handling untuk memproses input form dan validasi sebelum data dikirimkan ke server.
- Event yang diimplementasikan meliputi:
  - Validasi input saat mengetik.
  - Menonaktifkan tombol submit jika input tidak valid.
  - Pengiriman data menggunakan AJAX.

#### **Contoh Implementasi**

- File: `public/js/register.js`

  - **Event untuk validasi input**:
    ```javascript
    usernameInput.addEventListener("input", checkFormValidity);
    emailInput.addEventListener("input", checkFormValidity);
    passwordInput.addEventListener("input", checkFormValidity);
    confirmPasswordInput.addEventListener("input", checkFormValidity);
    termsCheckbox.addEventListener("change", checkFormValidity);
    ```
  - **Validasi input dengan JavaScript sebelum diproses oleh PHP**:

    ```javascript
    function validateEmail(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    }

    function validatePassword(password) {
      return password.trim().length >= 8;
    }

    function passwordsMatch(password, confirmPassword) {
      return password === confirmPassword;
    }

    function checkFormValidity() {
      const isUsernameValid = validateUsername(usernameInput.value);
      const isEmailValid = validateEmail(emailInput.value);
      const isPasswordValid = validatePassword(passwordInput.value);
      const isConfirmPasswordValid = passwordsMatch(
        passwordInput.value,
        confirmPasswordInput.value
      );
      submitButton.disabled = !(
        isUsernameValid &&
        isEmailValid &&
        isPasswordValid &&
        isConfirmPasswordValid
      );
    }
    ```

- File: `public/js/product.js`

  - **Event untuk menambah dan memperbarui produk**:

    ```javascript
    productForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new URLSearchParams({
        name: nameInput.value.trim(),
        description: descriptionInput.value.trim(),
        price: priceInput.value.trim(),
        image_url: imageUrlInput.value.trim(),
        stock: stockInput.value.trim(),
      });

      fetch(`../controllers/products_controller.php?action=create`, {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => alert(data.message || data.error))
        .catch((error) => console.error("Error:", error));
    });
    ```

---

### **Bagian 2: Server-side Programming (30%)**

#### **2.1 Pengelolaan Data dengan PHP (20%)**

##### **Deskripsi**

- Aplikasi menggunakan metode POST dan GET untuk mengelola data pada formulir, seperti pendaftaran pengguna dan manipulasi data produk.
- Validasi data dilakukan di sisi server untuk memastikan integritas data.
- Data seperti jenis browser dan alamat IP pengguna juga disimpan ke basis data untuk keperluan logging atau analitik.

##### **Contoh Implementasi**

1. **Menggunakan Metode POST/GET pada Formulir**:

   - File: `controllers/process_user.php`
     ```php
     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $username = htmlspecialchars($_POST['username']);
         $email = htmlspecialchars($_POST['email']);
         $password = htmlspecialchars($_POST['password']);
         $confirmPassword = htmlspecialchars($_POST['confirm_password']);
     }
     ```

2. **Validasi Data dari Variabel Global**:

   - File: `controllers/process_user.php`

     ```php
     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
         die("Email tidak valid.");
     }

     if ($password !== $confirmPassword) {
         die("Password dan konfirmasi password tidak cocok.");
     }
     ```

3. **Simpan Data ke Basis Data (termasuk browser dan IP pengguna)**:

   - File: `controllers/process_user.php`

     ```php
     $ip = $_SERVER['REMOTE_ADDR'];
     $browser = $_SERVER['HTTP_USER_AGENT'];

     if ($ip === '::1') {
         $ip = '127.0.0.1'; // Ubah IPv6 localhost ke IPv4
     }

     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

     $stmt = $conn->prepare("INSERT INTO users (name, email, password, ip_address, browser) VALUES (?, ?, ?, ?, ?)");
     $stmt->bind_param("sssss", $username, $email, $hashedPassword, $ip, $browser);

     if ($stmt->execute()) {
         echo "Data berhasil disimpan.";
     } else {
         echo "Gagal menyimpan data: " . $stmt->error;
     }
     ```

---

#### **2.2 Objek PHP Berbasis OOP (10%)**

##### **Deskripsi**

- Aplikasi menggunakan paradigma Object-Oriented Programming (OOP) untuk mengelola data pengguna.
- Kelas PHP dibuat untuk mengelola logika bisnis, seperti mencari pengguna berdasarkan email dan memverifikasi password.

##### **Contoh Implementasi**

1. **Kelas PHP dengan Minimal Dua Metode**:

   - File: `models/User.php`

     ```php
     class User
     {
         private $conn;

         public function __construct($db)
         {
             $this->conn = $db;
         }

         public function findByEmail($email)
         {
             $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
             $stmt->bind_param("s", $email);
             $stmt->execute();
             $result = $stmt->get_result();
             return $result->fetch_assoc();
         }

         public function verifyPassword($inputPassword, $storedHash)
         {
             return password_verify($inputPassword, $storedHash);
         }
     }
     ```

2. **Menggunakan Objek dalam Skenario Tertentu**:

   - File: `controllers/user_controller.php`

     ```php
     include '../models/User.php';

     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
         $email = htmlspecialchars($_POST['email']);
         $password = htmlspecialchars($_POST['password']);

         $userModel = new User($conn);
         $user = $userModel->findByEmail($email);

         if ($user && $userModel->verifyPassword($password, $user['password'])) {
             $_SESSION['user_id'] = $user['id'];
             $_SESSION['user_name'] = $user['name'];
             $_SESSION['is_logged_in'] = true;
             echo json_encode(["success" => "Login berhasil.", "redirect" => "dashboard.php"]);
         } else {
             echo json_encode(["error" => "Email atau password salah."]);
         }
     }
     ```

##### **Kesimpulan**

- **Pengelolaan Data dengan PHP**: Terdapat implementasi metode POST/GET, validasi input di sisi server, dan penyimpanan data (termasuk IP dan browser).
- **OOP**: Kelas `User` digunakan untuk pengelolaan data pengguna, dengan metode untuk pencarian data (`findByEmail`) dan verifikasi password (`verifyPassword`).

---

### **Bagian 3: Database Management (20%)**

#### **3.1 Pembuatan Tabel Database (5%)**

##### **Deskripsi**

- Repository ini mencakup pembuatan tabel database yang dirancang sesuai kebutuhan aplikasi, yaitu untuk pengguna (`users`) dan produk (`products`).

##### **Kode Implementasi**

- File: `config/manage-table.sql`

  ```sql
  CREATE TABLE users (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      email VARCHAR(255) NOT NULL UNIQUE,
      password VARCHAR(255) NOT NULL,
      ip_address VARCHAR(45),
      browser VARCHAR(255),
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  );

  CREATE TABLE products (
      id INT AUTO_INCREMENT PRIMARY KEY,
      name VARCHAR(255) NOT NULL,
      description TEXT,
      price DECIMAL(10, 2) NOT NULL,
      image_url VARCHAR(255),
      stock INT DEFAULT 0,
      created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
      updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
  );
  ```

---

#### **3.2 Konfigurasi Koneksi Database (5%)**

##### **Deskripsi**

- Repository ini menggunakan file konfigurasi untuk mengatur koneksi ke database, termasuk hostname, username, password, dan nama database.

##### **Kode Implementasi**

- File: `config/db_config.php`

  ```php
  <?php
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "pemweb-uas-akhdan";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
      die("Koneksi gagal: " . $conn->connect_error);
  }
  ?>
  ```

---

#### **3.3 Manipulasi Data pada Database (10%)**

##### **Deskripsi**

- Repository ini mengimplementasikan operasi CRUD (Create, Read, Update, Delete) untuk tabel `users` dan `products`.

##### **Kode Implementasi**

1. **Create Data (Tambah Data)**:

   - File: `controllers/process_user.php`

     ```php
     $stmt = $conn->prepare("INSERT INTO users (name, email, password, ip_address, browser) VALUES (?, ?, ?, ?, ?)");
     $stmt->bind_param("sssss", $username, $email, $hashedPassword, $ip, $browser);
     $stmt->execute();
     ```

   - File: `controllers/products_controller.php` (Tambah Produk)
     ```php
     $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, stock) VALUES (?, ?, ?, ?, ?)");
     $stmt->bind_param("ssdsi", $name, $description, $price, $image_url, $stock);
     $stmt->execute();
     ```

2. **Read Data (Ambil Data)**:

   - File: `controllers/products_controller.php` (Ambil Semua Produk)

     ```php
     $query = "SELECT * FROM products";
     $result = $conn->query($query);
     while ($row = $result->fetch_assoc()) {
         $data[] = $row;
     }
     echo json_encode($data);
     ```

   - File: `controllers/products_controller.php` (Ambil Produk Berdasarkan ID)
     ```php
     $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
     $stmt->bind_param("i", $id);
     $stmt->execute();
     $result = $stmt->get_result();
     $data = $result->fetch_assoc();
     echo json_encode($data);
     ```

3. **Update Data (Perbarui Data)**:

   - File: `controllers/products_controller.php`
     ```php
     $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, image_url = ?, stock = ? WHERE id = ?");
     $stmt->bind_param("ssdssi", $name, $description, $price, $image_url, $stock, $id);
     $stmt->execute();
     ```

4. **Delete Data (Hapus Data)**:
   - File: `controllers/products_controller.php`
     ```php
     $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
     $stmt->bind_param("i", $id);
     $stmt->execute();
     ```

---

### **Kesimpulan**

- **Pembuatan Tabel**: Tabel `users` dan `products` dibuat untuk memenuhi kebutuhan aplikasi.
- **Koneksi Database**: Konfigurasi koneksi ke database dilakukan di `config/db_config.php`.
- **CRUD Data**: Operasi Create, Read, Update, dan Delete diimplementasikan untuk data pengguna dan produk.

---

### **Bagian 4: State Management (20%)**

#### **4.1 State Management dengan Session (10%)**

##### **Deskripsi**

- Repository ini menggunakan PHP session untuk mengelola informasi pengguna, seperti status login, nama pengguna, dan data keranjang belanja.
- Fungsi `session_start()` digunakan untuk memulai sesi di berbagai file, dan informasi pengguna disimpan dalam session.

##### **Kode Implementasi**

1. **Memulai Sesi dengan `session_start()`**:

   - File: `session/session.php`

     ```php
     session_start();
     if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
         header("Location: ../views/login.php");
         exit;
     }

     $userName = $_SESSION['user_name'];
     ```

   - File: `views/dashboard.php`
     ```php
     session_start();
     if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
         header("Location: login.php");
         exit;
     }
     ```

2. **Menyimpan Informasi Pengguna ke Dalam Session**:

   - File: `controllers/user_controller.php`
     ```php
     if ($user && $userModel->verifyPassword($password, $user['password'])) {
         // Simpan data user ke session
         $_SESSION['user_id'] = $user['id'];
         $_SESSION['user_name'] = $user['name'];
         $_SESSION['ip_address'] = $user['ip_address'];
         $_SESSION['browser'] = $user['browser'];
         $_SESSION['is_logged_in'] = true;
     }
     ```

3. **Mengelola Keranjang Belanja dengan Session**:
   - File: `views/cart.php`
     ```php
     session_start();
     $sessionCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
     ```

---

#### **4.2 Pengelolaan State dengan Cookie dan Browser Storage (10%))**

##### **Deskripsi**

- Cookie digunakan untuk menyimpan data sementara yang dapat diakses di seluruh aplikasi.
- Browser Storage (LocalStorage dan SessionStorage) digunakan untuk menyimpan data secara lokal di browser pengguna, seperti keranjang belanja.

##### **Kode Implementasi**

1. **Fungsi untuk Cookie**:

   - File: `views/storage-management.html`

     ```javascript
     document.getElementById("setCookie").addEventListener("click", () => {
       const value = cookieInput.value.trim();
       if (!value) {
         alert("Masukkan nilai terlebih dahulu!");
         return;
       }
       document.cookie = `thebugitselfCookie=${value}; path=/; max-age=3600`; // Berlaku 1 jam
       updateDisplays();
     });

     document.getElementById("deleteCookie").addEventListener("click", () => {
       document.cookie = "thebugitselfCookie=; path=/; max-age=0"; // Hapus cookie
       updateDisplays();
     });
     ```

2. **Penggunaan LocalStorage untuk Keranjang Belanja**:

   - File: `views/cart.php`

     ```javascript
     document.addEventListener("DOMContentLoaded", function () {
       const cart = JSON.parse(localStorage.getItem("cart")) || {};
       const tableBody = document.getElementById("localStorageCart");
       let total = 0;

       Object.entries(cart).forEach(([productId, product]) => {
         const row = document.createElement("tr");
         const subtotal = product.price * product.quantity;
         total += subtotal;

         row.innerHTML = `
                 <td>${product.name}</td>
                 <td>Rp ${parseFloat(product.price).toLocaleString(
                   "id-ID"
                 )}</td>
                 <td>${product.quantity}</td>
                 <td>Rp ${subtotal.toLocaleString("id-ID")}</td>
             `;
         tableBody.appendChild(row);
       });

       document.getElementById(
         "localTotal"
       ).textContent = `Rp ${total.toLocaleString("id-ID")}`;
     });
     ```

3. **Penggunaan SessionStorage di Detail Produk**:

   - File: `views/detail.php`

     ```javascript
     function addToCart() {
       const productId = document.getElementById("productId").value;
       const quantity = parseInt(
         document.getElementById("quantityInputLocal").value
       );
       const productName = "<?php echo htmlspecialchars($product['name']); ?>";
       const productPrice = parseFloat(
         "<?php echo floatval($product['price']); ?>"
       );

       let cart = JSON.parse(localStorage.getItem("cart")) || {};

       if (cart[productId]) {
         cart[productId].quantity += quantity;
       } else {
         cart[productId] = {
           name: productName,
           price: productPrice,
           quantity: quantity,
         };
       }

       localStorage.setItem("cart", JSON.stringify(cart));
       alert("Produk berhasil ditambahkan ke keranjang localStorage!");
     }
     ```

---

### **Kesimpulan**

- **State Management dengan Session**:

  - `session_start()` digunakan di berbagai file untuk memulai sesi.
  - Informasi pengguna dan keranjang belanja disimpan dalam session.

- **State Management dengan Cookie dan Browser Storage**:
  - Cookie dikelola menggunakan JavaScript di `views/storage-management.html`.
  - LocalStorage digunakan untuk menyimpan data keranjang belanja di browser.
  - SessionStorage digunakan untuk menyimpan data sementara saat pengguna berada di halaman tertentu.

---

### **Bagian Bonus: Hosting Aplikasi Web (20%)**

#### **Langkah-langkah untuk Meng-host Aplikasi Web**

1. Siapkan file codingan yang telah selesai dan siap di-upload.
2. Login ke platform hosting **InfinityFree**.
3. Setup database MySQL di InfinityFree melalui kontrol panel.
4. Buat akun untuk database (username dan password) yang disediakan oleh InfinityFree.
5. Sesuaikan file `config/db_config.php` dengan detail konfigurasi database yang diberikan oleh InfinityFree.

---

#### **Penyedia Hosting yang Dipilih: InfinityFree**

- **Alasan memilih InfinityFree**:
  - Platform **gratis** dan mendukung PHP serta MySQL.
  - Cocok untuk proyek skala kecil hingga menengah.
  - Memiliki panel kontrol sederhana yang memudahkan pengguna pemula.

---

#### **Keamanan Aplikasi Web**

- InfinityFree memiliki sistem keamanan:
  - Password akun yang **digenerate secara acak**, sehingga lebih sulit diretas.
  - Mendukung **HTTPS** untuk mengenkripsi data antara server dan klien.
- Selain itu, aplikasi menggunakan mekanisme sanitasi input untuk mencegah serangan XSS dan SQL Injection.

---

#### **Konfigurasi Server**

1. **Setup Database**:

   - Pastikan nama database, username, dan password sesuai dengan detail dari InfinityFree.
   - Update file `config/db_config.php`:
     ```php
     $servername = "sqlXXX.infinityfree.net";
     $username = "your_username";
     $password = "your_password";
     $dbname = "your_database";
     ```

2. **Struktur File**:

   - Letakkan file `index.php` di dalam folder `htdocs` di root direktori hosting.

3. **Tes Koneksi dan Fungsi Aplikasi**:
   - Akses aplikasi melalui URL yang diberikan oleh InfinityFree.
   - Periksa apakah koneksi database berhasil dan fitur aplikasi berjalan dengan baik.
