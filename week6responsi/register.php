<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<body>
    <?php
    session_start();
    if (isset($_SESSION['result'])) {

        if ($_SESSION['result'] == 'sukses') //regis sukses
            echo '<p> Registrasi Sukses </p>';
        else if ($_SESSION['result'] == 'gagal') //regis gagal
            echo '<p> Registrasi Gagal </p>';

        unset($_SESSION['result']);
    }

    ?>
    <form action="register_proses.php" method="post">
        <label>Username</label> <input type="text" name="username" required><br>
        <label>Fullname</label> <input type="text" name="fullname" required><br>
        <label>Password</label> <input type="password" name="password" autocomplete="off" required><br>
        <p>Already have account ? <a href="login.php">Log In</a></p>

        <input type="submit" name="btnregis" value="Register">

    </form>
</body>

</html>