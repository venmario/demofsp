<?php
session_start();
if (isset($_POST['btnlogout'])) {
    unset($_SESSION['username']);
    unset($_SESSION['nama']);
    header('Location: login.php');
}
