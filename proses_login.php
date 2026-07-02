<?php
session_start();

$passwordSistem = "ppdbesut123";

$passwordInput = $_POST['password'];

if ($passwordInput === $passwordSistem) {

    $_SESSION['login'] = true;

    header("Location: dashboard.php");
    exit;

} else {

    header("Location: login.php?error=1");
    exit;

}
?>