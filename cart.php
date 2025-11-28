<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

// Update produk dengan harga yang realistis
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

if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity == 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
    }
}

if (isset($_POST['remove_item'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
}

if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
}

$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - LokaMart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS Fallback untuk cart */
        :root {
            --primary-brown: #8B7355;
            --secondary-brown: #A52A2A;
            --light-brown: #D2B48C;
            --cream: #F5F5DC;
            --dark-brown: #654321;
            --text-dark: #5D4037;
            --text-light: #8D6E63;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3498db;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--cream) 0%, #FFF8DC 100%);
            color: var(--text-dark);
            min-height: 100vh;
            margin: 0;
        }
        
        /* Navigation */
        nav {
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--dark-brown) 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 20px rgba(139, 115, 85, 0.3);
        }
        
        .nav-brand h1 {
            font-size: 1.8rem;
            margin-bottom: 0.2rem;
        }
        
        .tagline {
            font-size: 0.9rem;
            opacity: 0.9;
            font-style: italic;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .welcome {
            opacity: 0.9;
            font-weight: 500;
        }
        
        .nav-link {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .cart-link {
            position: relative;
        }
        
        .cart-count {
            background: var(--secondary-brown);
            color: white;
            border-radius: 50%;
            padding: 0.2rem 0.5rem;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        
        /* Container */
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .page-header h2 {
            color: var(--primary-brown);
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }
        
        .page-header p {
            color: var(--text-light);
            font-size: 1.1rem;
        }
        
        /* Cart Table */
        .cart-table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(139, 115, 85, 0.1);
            margin-bottom: 2rem;
            overflow-x: auto;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }
        
        .cart-table th {
            background: var(--primary-brown);
            color: white;
            padding: 1.2rem;
            text-align: left;
            font-weight: 600;
        }
        
        .cart-table td {
            padding: 1.2rem;
            border-bottom: 1px solid var(--light-brown);
            vertical-align: middle;
        }
        
        .product-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            background: var(--cream);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-name {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .price-cell {
            font-weight: 600;
            color: var(--secondary-brown);
        }
        
        .quantity-input {
            width: 70px;
            padding: 0.5rem;
            border: 2px solid var(--light-brown);
            border-radius: 5px;
            text-align: center;
            font-size: 1rem;
        }
        
        .btn-remove {
            background: var(--danger);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .btn-remove:hover {
            background: #c0392b;
        }
        
        /* Cart Actions */
        .cart-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .btn-secondary, .btn-danger, .btn-primary, .btn-back {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-weight: 600;
            transition: all 0.3s ease;
            text-align: center;
            font-size: 0.9rem;
        }
        
        .btn-secondary {
            background: var(--light-brown);
            color: var(--text-dark);
        }
        
        .btn-secondary:hover {
            background: var(--primary-brown);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c0392b;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 115, 85, 0.4);
        }
        
        .btn-back {
            background: var(--text-light);
            color: white;
        }
        
        .btn-back:hover {
            background: var(--text-dark);
        }
        
        /* Cart Summary */
        .cart-summary {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(139, 115, 85, 0.1);
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .total-section h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .total-amount {
            color: var(--secondary-brown);
            font-size: 2rem;
        }
        
        .btn-checkout {
            font-size: 1.1rem;
            padding: 1rem 2rem;
            margin-top: 1rem;
        }
        
        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(139, 115, 85, 0.1);
        }
        
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            opacity: 0.7;
        }
        
        .empty-cart h3 {
            color: var(--text-dark);
            margin-bottom: 1rem;
        }
        
        .empty-cart p {
            color: var(--text-light);
            margin-bottom: 2rem;
        }
        
        /* Navigation */
        .navigation {
            text-align: center;
            margin-top: 2rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }
            
            nav {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            .cart-actions {
                flex-direction: column;
            }
            
            .cart-table {
                font-size: 0.9rem;
            }
            
            .cart-table th,
            .cart-table td {
                padding: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-brand">
            <h1>🍎 LokaMart</h1>
            <span class="tagline">Fresh Foods</span>
        </div>
        <div class="nav-links">
            <span class="welcome">Halo, <?php echo $_SESSION['user']; ?> 👋</span>
            <a href="products.php" class="nav-link">🏠 Produk</a>
            <a href="cart.php" class="nav-link cart-link active">
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
            <h2>🛒 Keranjang Belanja</h2>
            <p>Kelompokkan pesanan Anda</p>
        </div>
        
        <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
            <div class="empty-cart">
                <div class="empty-icon">🛒</div>
                <h3>Keranjang belanja kosong</h3>
                <p>Yuk, tambahkan makanan favorit Anda!</p>
                <a href="products.php" class="btn-primary">Mulai Belanja</a>
            </div>
        <?php else: ?>
            <form method="POST">
                <div class="cart-table-container">
                    <table class="cart-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <div class="product-image">
                                            <img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>"
                                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjEyIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+'">
                                        </div>
                                        <div>
                                            <div class="product-name"><?php echo $item['name']; ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="price-cell">Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></td>
                                <td>
                                    <input type="number" name="quantity[<?php echo $product_id; ?>]" 
                                           value="<?php echo $item['quantity']; ?>" min="0" max="99" class="quantity-input">
                                </td>
                                <td class="price-cell">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></td>
                                <td>
                                    <button type="submit" name="remove_item" class="btn-remove" 
                                            onclick="this.form['product_id'].value='<?php echo $product_id; ?>'">
                                        🗑️ Hapus
                                    </button>
                                    <input type="hidden" name="product_id">
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="cart-actions">
                    <button type="submit" name="update_cart" class="btn-secondary">🔄 Update Keranjang</button>
                    <button type="submit" name="clear_cart" class="btn-danger">🗑️ Kosongkan Keranjang</button>
                </div>
            </form>
            
            <div class="cart-summary">
                <div class="total-section">
                    <h3>Total Belanja: <span class="total-amount">Rp <?php echo number_format($total, 0, ',', '.'); ?></span></h3>
                    <a href="checkout.php" class="btn-primary btn-checkout">🚀 Lanjut ke Checkout</a>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="navigation">
            <a href="products.php" class="btn-back">← Kembali Belanja</a>
        </div>
    </div>
</body>
</html>