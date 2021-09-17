<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: index.php');
}
?>

<body>
    <?php
    if (isset($_SESSION['result'])) {
        if ($_SESSION['result'] == 'gagal')
            echo "<p>Username atau Password salah!</p>";
        unset($_SESSION['result']);
    } else if (isset($_SESSION['login'])) {
        echo $_SESSION['login'];
        unset($_SESSION['login']);
    }
    ?>
    <form action="login_proses.php" method="post" required>
        Username : <input type="text" name="iduser" required><br>
        Password : <input type="password" name="password" required><br>

        <p>Don't have account ? <a href="register.php">Sign Up Now!</a></p>
        <input type="submit" name="btnsubmit" value="Login">
    </form>
</body>

</html>