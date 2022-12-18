<?php 
include 'array.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Title</title>
</head>

<body>
    <ul>
        <?php 
        foreach ($foods as $food ) {
            echo "<li><a href='halaman2.php?id=". $food['id'] ."'>" .$food['nama']. "</a></li>";
        }
        ?>
    </ul>

</body>

</html>