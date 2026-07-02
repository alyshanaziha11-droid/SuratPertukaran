<?php
session_start();

if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem Surat PPD Besut</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            color: #1f1f1f;
        }

        body::before {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            background: #9B0000;
            top: -180px;
            left: -180px;
            transform: rotate(45deg);
            border-radius: 35px;
            opacity: 0.95;
        }

        body::after {
            content: "";
            position: absolute;
            width: 460px;
            height: 460px;
            background: #C98585;
            right: -180px;
            top: 80px;
            transform: rotate(45deg);
            border-radius: 35px;
            opacity: 0.75;
        }

        .shape-soft {
            position: absolute;
            width: 230px;
            height: 230px;
            background: #F8E4E4;
            bottom: -80px;
            left: -80px;
            transform: rotate(45deg);
            border-radius: 30px;
        }

        .login-container {
            position: relative;
            z-index: 2;
            width: 430px;
            background: rgba(255, 255, 255, 0.96);
            border: 1px solid #e5e5e5;
            border-radius: 18px;
            padding: 38px 42px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.12);
            text-align: center;
        }

        .logo {
            width: 105px;
            margin-bottom: 18px;
        }

        .subtitle {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #555;
            margin-bottom: 5px;
        }

        h2 {
            margin: 0;
            font-size: 24px;
            line-height: 1.25;
            color: #111;
            text-transform: uppercase;
        }

        .highlight {
            color: #9B0000;
            letter-spacing: 2px;
        }

        .divider {
            width: 80px;
            height: 4px;
            background: #9B0000;
            margin: 22px auto 28px;
            border-radius: 20px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #cfcfcf;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
        }

        input[type="password"]:focus {
            border-color: #9B0000;
            box-shadow: 0 0 0 3px rgba(155, 0, 0, 0.12);
        }

        button {
            width: 100%;
            padding: 13px;
            background: #9B0000;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            letter-spacing: 0.5px;
        }

        button:hover {
            background: #6E0000;
        }

        .error {
            background: #ffe7e7;
            color: #9B0000;
            padding: 10px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 18px;
            border: 1px solid #ffc4c4;
        }

        .footer {
            margin-top: 22px;
            font-size: 12px;
            color: #666;
            line-height: 1.5;
        }
    </style>
</head>

<body>

<div class="shape-soft"></div>

<div class="login-container">

    <img src="uploads/Logo-Korporat-KPM-BI-Tulisan-Putih.png" class="logo">

    <div class="subtitle">SISTEM RASMI</div>

    <h2>
        Sistem Penjanaan<br>
        <span class="highlight">Surat Pertukaran Murid</span>
    </h2>

    <div class="divider"></div>

    <?php if (isset($_GET['error'])): ?>
        <div class="error">
            Password salah. Sila cuba lagi.
        </div>
    <?php endif; ?>

    <form action="proses_login.php" method="POST">

        <div class="form-group">
            <label>Password Sistem</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>

        <button type="submit">LOG MASUK</button>

    </form>

    <div class="footer">
        Pejabat Pendidikan Daerah Besut<br>
        Kementerian Pendidikan Malaysia
    </div>

</div>

</body>
</html>