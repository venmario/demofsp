<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
    } else {
        echo "<p>Hello " . $_SESSION['username'] . "</p>";
        echo "<p>Hello " . $_SESSION['nama'] . "</p>";
    }
    ?>

    <form action="logout_proses.php" method="post">
        <input type="submit" name="btnlogout" value="Logout">
    </form>
</body>

</html>