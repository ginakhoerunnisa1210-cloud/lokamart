<?php
session_start();
if (isset($_POST['login'])) {
    $_SESSION['user'] = $_POST['username'];
    header('Location: products.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LokaMart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Fallback CSS jika style.css tidak load */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #F5F5DC 0%, #FFF8DC 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-container {
            background: white;
            padding: 2.5rem;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(139, 115, 85, 0.15);
            width: 100%;
            max-width: 400px;
        }
        
        .brand-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .brand-header .logo {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }
        
        .brand-header h1 {
            color: #8B7355;
            font-size: 2.2rem;
            margin: 0.5rem 0;
            font-weight: 700;
        }
        
        .brand-header .tagline {
            color: #8D6E63;
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #5D4037;
        }
        
        .form-group input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #D2B48C;
            border-radius: 10px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #8B7355;
            box-shadow: 0 0 0 3px rgba(139, 115, 85, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #8B7355, #A52A2A);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 115, 85, 0.4);
        }
        
        .demo-note {
            margin-top: 1.5rem;
            color: #8D6E63;
            text-align: center;
            padding: 1rem;
            background: #F5F5DC;
            border-radius: 8px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="brand-header">
            <div class="logo">🍎</div>
            <h1>LokaMart</h1>
            <p class="tagline">Toko Makanan Segar & Berkualitas</p>
        </div>
        
        <form method="POST" class="login-form">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            
            <button type="submit" name="login" class="btn-login">Masuk</button>
        </form>
        
        <p class="demo-note">Demo: Gunakan username/password apa saja</p>
    </div>
</body>
</html>