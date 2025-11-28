<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$products = [
    ['id' => 1, 'name' => 'Apel Fuji', 'price' => 25000, 'image' => 'apple.jpg'],
    ['id' => 2, 'name' => 'Pisang Cavendish', 'price' => 15000, 'image' => 'banana.jpg'],
    ['id' => 3, 'name' => 'Jeruk Mandarin', 'price' => 30000, 'image' => 'orange.jpg'],
    ['id' => 4, 'name' => 'Anggur Import', 'price' => 45000, 'image' => 'grape.jpg'],
    ['id' => 5, 'name' => 'Strawberry', 'price' => 35000, 'image' => 'strawberry.jpg'],
    ['id' => 6, 'name' => 'Semangka', 'price' => 20000, 'image' => 'watermelon.jpg'],
    ['id' => 7, 'name' => 'Roti Tawar', 'price' => 12000, 'image' => 'bread.jpg'],
    ['id' => 8, 'name' => 'Keju Cheddar', 'price' => 28000, 'image' => 'cheese.jpg']
];

if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product = $products[array_search($product_id, array_column($products, 'id'))];
    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => 1
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <style>
.products-grid {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 20px !important;
}

.product-card {
    background: white !important;
    padding: 20px !important;
    border-radius: 10px !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1) !important;
}

@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - LokaMart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="nav-brand">
            <h1>🍎 LokaMart</h1>
            <span class="tagline">Fresh Foods</span>
        </div>
        <div class="nav-links">
            <span class="welcome">Halo, <?php echo $_SESSION['user']; ?> 👋</span>
            <a href="products.php" class="nav-link active">🏠 Produk</a>
            <a href="cart.php" class="nav-link cart-link">
                🛒 Keranjang 
                <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                    <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                <?php endif; ?>
            </a>
            <a href="index.php?logout=1" class="nav-link logout">🚪 Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h2>🛍️ Daftar Produk</h2>
            <p>Pilih makanan segar favorit Anda</p>
        </div>
        
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" 
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtc2l6ZT0iMTgiIHRleHQtYW5jaG9yPSJtaWRkbGUiIGR5PSIuM2VtIj5ObyBJbWFnZTwvdGV4dD48L3N2Zz4='">
                </div>
                <div class="product-content">
                    <h3 class="product-name"><?php echo $product['name']; ?></h3>
                    <p class="product-price">Rp <?php echo number_format($product['price'], 0, ',', '.'); ?></p>
                    <form method="POST" class="product-form">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" name="add_to_cart" class="btn-add-to-cart">
                            + Tambah ke Keranjang
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>