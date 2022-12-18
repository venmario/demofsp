<?php 
include 'array.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}
else if(isset($_POST['idmakanan'])){
    $id = $_POST['idmakanan'];
}
else{
    header('location: index.php');
}
die(var_dump($_POST));

foreach ($foods as $food ) {
    if ($food['id'] == $id) {
        echo "<ul>";
            echo "<li>Nama : ". $food['nama'] ."</li>";
            echo "<li>Harga : ". $food['harga'] ."</li>";
            echo "<li>Deskripsi : ". $food['deskripsi'] ."</li>";
        echo "</ul>";
        break;
    }
}

?>