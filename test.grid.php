<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Grid</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div class="nav-brand">
            <h1>🍎 LokaMart</h1>
            <span class="tagline">Test Grid</span>
        </div>
    </nav>
    
    <div class="container">
        <h2>Test CSS Grid</h2>
        <div class="products-grid">
            <div class="product-card" style="background: #e74c3c; color: white; padding: 2rem;">
                <h3>Product 1</h3>
                <p>Ini harus muncul dalam grid</p>
            </div>
            <div class="product-card" style="background: #3498db; color: white; padding: 2rem;">
                <h3>Product 2</h3>
                <p>Ini harus muncul dalam grid</p>
            </div>
            <div class="product-card" style="background: #27ae60; color: white; padding: 2rem;">
                <h3>Product 3</h3>
                <p>Ini harus muncul dalam grid</p>
            </div>
            <div class="product-card" style="background: #f39c12; color: white; padding: 2rem;">
                <h3>Product 4</h3>
                <p>Ini harus muncul dalam grid</p>
            </div>
        </div>
    </div>
</body>
</html>