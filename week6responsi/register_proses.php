<?php
session_start();
include 'koneksi.php';
require_once('user.php');

if (isset($_POST['btnregis'])) {
    $iduser = $_POST['username'];
    $fullname = $_POST['fullname'];
    $password = $_POST['password'];


    $user = new User($SERVER, $USER, $PASS, $DB);
    $hasil = $user->Register($iduser, $fullname, $password);
    echo $hasil;
    if ($hasil > 0) {
        $_SESSION['result'] = 'sukses';
        header('Location: register.php');
    } else {
        $_SESSION['result'] = 'gagal';
        header('Location: register.php');
    }
} else {
    header('Location: register.php');
}
