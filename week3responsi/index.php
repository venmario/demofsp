<!DOCTYPE html>
<html>

<head>
    <title>FSP - Database</title>
    <style type="text/css">
        table,
        td,
        th {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <?php
    $koneksi = new mysqli("localhost", "root", "", "my_schema");

    if ($koneksi->connect_errno) {
        die("Failed to connect to MySQL: " . $koneksi->connect_error);
    }

    echo "<br>Ready to Process Database<br>";

    // if (isset($_GET['judul'])) {
    //     $judul = $_GET['judul'];
    //     $sql = "SELECT * FROM movie WHERE judul LIKE '%$judul%'";
    // } else
    //     $sql = "SELECT * FROM movie";
    // $hasil = $koneksi->query($sql);

    $judul = (isset($_GET['judul'])) ? $_GET['judul'] : '';
    $sql = "SELECT * FROM movie WHERE judul LIKE '%$judul%'";
    $hasil = $koneksi->query($sql);


    //$baris = $hasil->fetch_assoc();
    //echo $baris["judul"]."<BR>";
    ?>
    <form method="get" action="">
        Keyword: <input type="text" name="judul" value="<?= $judul ?>" />
        <input type="submit" name="submit" value="Search">
    </form>
    <br>
    <?php
    echo "<table>";
    echo "<tr><th>Gambar</th><th>Judul</th><th>Tgl Rilis</th><th>Serial</th><th>Genre</th><th>Aksi</th></tr>";

    while ($baris = $hasil->fetch_assoc()) {
        echo "<tr>";

        $idmovie = $baris['idmovie'];
        // $gambar = "gambar/" . $baris["idmovie"] . "." . $baris['extention'];
        $sql = 'SELECT * FROM gambar WHERE idmovie = ?';
        $stmt = $koneksi->prepare($sql);
        $stmt->bind_param("i", $idmovie);
        $stmt->execute();
        $result = $stmt->get_result();
        echo "<td>";
        while ($row = $result->fetch_assoc()) {
            $idgambar = $row['idgambar']; //10
            $ext = $row['extention']; //.jpg
            $fullname = $idgambar . "." . $ext;
            echo "<img src='gambar/$fullname'>";
        }
        echo "</td>";

        echo "<td>" . $baris["judul"] . "</td>";
        echo "<td>" . date("d F Y", strtotime($baris["rilis"])) . "</td>";
        echo "<td>";
        // if ($baris["serial"] == 0)
        //     echo "Tidak";
        // else
        //     echo "Ya";
        echo ($baris['serial'] == 0) ? 'TIDAK' : 'SERIAL';
        echo "</td>";

        $sql2 = "SELECT G.nama FROM genre_movie as GM INNER JOIN genre as G ON GM.idgenre=G.idgenre WHERE GM.idmovie=" . $baris['idmovie'];
        $hasil2 = $koneksi->query($sql2);
        echo "<td>";
        while ($baris2 = $hasil2->fetch_assoc()) {
            echo "|" . $baris2["nama"] . "|";
        }
        echo "</td>";
        echo "<td>";
        echo "<a href='editform.php?id=$idmovie'>Edit</a> ";
        echo "<a href='deleteform.php?id=$idmovie'>Delete</a> ";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";





    $koneksi->close();
    ?>
    <br>
    <a href="insertform.php">Tambah Movie</a>

</body>

</html>