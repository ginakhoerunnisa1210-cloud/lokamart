<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: products.php');
    exit;
}

if (isset($_POST['confirm_payment'])) {
    // Simulasi proses pembayaran berhasil
    $order_number = 'LM' . date('YmdHis');
    $order_items = $_SESSION['cart'];
    $total_amount = 0;
    
    foreach ($_SESSION['cart'] as $item) {
        $total_amount += $item['price'] * $item['quantity'];
    }
    
    // Clear cart setelah checkout
    unset($_SESSION['cart']);
    
    // Simpan data pesanan (dalam session untuk demo)
    $_SESSION['last_order'] = [
        'order_number' => $order_number,
        'items' => $order_items,
        'total' => $total_amount,
        'date' => date('d/m/Y H:i:s')
    ];
    
    header('Location: checkout.php?success=1');
    exit;
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
    <title>Checkout - LokaMart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* CSS Fallback untuk checkout */
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
        
        /* Checkout Container */
        .checkout-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        
        @media (max-width: 768px) {
            .checkout-container {
                grid-template-columns: 1fr;
            }
        }
        
        /* Order Summary */
        .order-summary {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(139, 115, 85, 0.1);
        }
        
        .order-summary h3 {
            color: var(--primary-brown);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light-brown);
            font-size: 1.3rem;
        }
        
        .order-items {
            margin-bottom: 1.5rem;
        }
        
        .order-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid var(--light-brown);
        }
        
        .order-item:last-child {
            border-bottom: none;
        }
        
        .order-item .product-image {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            overflow: hidden;
            background: var(--cream);
            margin-right: 1rem;
        }
        
        .order-item .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-details strong {
            display: block;
            margin-bottom: 0.3rem;
            color: var(--text-dark);
        }
        
        .item-details span {
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .item-total {
            font-weight: 600;
            color: var(--secondary-brown);
        }
        
        .order-total {
            text-align: right;
            padding-top: 1rem;
            margin-top: 1rem;
            border-top: 2px solid var(--light-brown);
            font-size: 1.2rem;
            color: var(--secondary-brown);
            font-weight: 600;
        }
        
        /* Payment Form */
        .payment-form {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(139, 115, 85, 0.1);
        }
        
        .payment-form h3 {
            color: var(--primary-brown);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light-brown);
            font-size: 1.3rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--light-brown);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #fefefe;
            font-family: inherit;
        }
        
        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: var(--primary-brown);
            background: white;
            box-shadow: 0 0 0 3px rgba(139, 115, 85, 0.1);
        }
        
        .form-group input::placeholder, .form-group textarea::placeholder {
            color: #999;
        }
        
        .btn-payment {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }
        
        .btn-payment:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 115, 85, 0.4);
        }
        
        /* Success Message */
        .success-message {
            text-align: center;
            padding: 4rem 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(139, 115, 85, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .success-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
        }
        
        .success-message h2 {
            color: var(--primary-brown);
            margin-bottom: 1.5rem;
        }
        
        .order-details {
            background: var(--cream);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 2rem 0;
            text-align: left;
        }
        
        .order-details p {
            margin-bottom: 0.5rem;
        }
        
        .thank-you {
            font-size: 1.2rem;
            color: var(--primary-brown);
            margin-bottom: 2rem;
        }
        
        .btn-continue {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, var(--primary-brown) 0%, var(--secondary-brown) 100%);
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-continue:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 115, 85, 0.4);
        }
        
        /* Navigation */
        .navigation {
            text-align: center;
            margin-top: 2rem;
        }
        
        .btn-back {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background: var(--text-light);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: var(--text-dark);
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
            
            .page-header h2 {
                font-size: 2rem;
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
        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">
                <div class="success-icon">✅</div>
                <h2>Pembayaran Berhasil!</h2>
                <div class="order-details">
                    <p><strong>Nomor Pesanan:</strong> <?php echo $_SESSION['last_order']['order_number']; ?></p>
                    <p><strong>Total:</strong> Rp <?php echo number_format($_SESSION['last_order']['total'], 0, ',', '.'); ?></p>
                    <p><strong>Tanggal:</strong> <?php echo $_SESSION['last_order']['date']; ?></p>
                </div>
                <p class="thank-you">Terima kasih telah berbelanja di LokaMart! 🎉</p>
                <a href="products.php" class="btn-continue">🛍️ Lanjutkan Belanja</a>
            </div>
        <?php else: ?>
            <div class="page-header">
                <h2>💰 Checkout</h2>
                <p>Lengkapi informasi pembayaran</p>
            </div>
            
            <div class="checkout-container">
                <div class="order-summary">
                    <h3>📦 Ringkasan Pesanan</h3>
                    <div class="order-items">
                        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                        <div class="order-item">
                            <div class="product-image">
                                <img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>"
                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2RkZCIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjEwIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+Tm8gSW1hZ2U8L3RleHQ+PC9zdmc+'">
                            </div>
                            <div class="item-details">
                                <strong><?php echo $item['name']; ?></strong>
                                <span><?php echo $item['quantity']; ?> x Rp <?php echo number_format($item['price'], 0, ',', '.'); ?></span>
                            </div>
                            <span class="item-total">Rp <?php echo number_format($item['price'] * $item['quantity'], 0, ',', '.'); ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="order-total">
                        <strong>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></strong>
                    </div>
                </div>

                <div class="payment-form">
                    <h3>💳 Informasi Pembayaran</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="fullname">Nama Lengkap:</label>
                            <input type="text" id="fullname" name="fullname" placeholder="Masukkan nama lengkap" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="address">Alamat Pengiriman:</label>
                            <textarea id="address" name="address" placeholder="Masukkan alamat lengkap pengiriman" required rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="payment_method">Metode Pembayaran:</label>
                            <select id="payment_method" name="payment_method" required>
                                <option value="">Pilih metode...</option>
                                <option value="credit">Kartu Kredit</option>
                                <option value="debit">Kartu Debit</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="ewallet">E-Wallet</option>
                                <option value="cod">Cash on Delivery</option>
                            </select>
                        </div>
                        
                        <button type="submit" name="confirm_payment" class="btn-payment">
                            ✅ Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="navigation">
                <a href="cart.php" class="btn-back">← Kembali ke Keranjang</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>