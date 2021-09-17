<?php
session_start();
include 'koneksi.php';
require_once('user.php');

if (isset($_POST['btnsubmit'])) {
    $username = $_POST['iduser'];
    $password = $_POST['password'];

    $user = new User($SERVER, $USER, $PASS, $DB);
    $hasil = $user->DoLogin($username, $password);

    if ($hasil == 1) {
        //login berhasil
        header("location: index.php");
    } else {
        //login gagal
        $_SESSION['result'] = 'gagal';
        header("location: login.php");
    }
}
