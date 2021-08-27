<!DOCTYPE html>
<html>

<head>
    <title>FSP - Database</title>
</head>

<body>
    <?php
    $koneksi = new mysqli("localhost", "root", "", "my_schema");

    if ($koneksi->connect_errno) {
        die("Failed to connect to MySQL: " . $koneksi->connect_error);
    }

    echo "<br>Ready to Process Database<br>";

    $idmovie = $_POST['idmovie'];
    $judul = $_POST['judul'];
    $rilis = $_POST['rilis'];
    $skor = $_POST['skor'];
    $sinopsis = $_POST['sinopsis'];
    $serial = $_POST['serial'];
    $genre = $_POST['genre'];
    $gambar = $_FILES['gambar'];

    // echo "<pre>";
    // print_r($_POST);
    // print_r($_FILES);
    // echo "</pre>";
    // die();


    $sql = "UPDATE movie SET judul=?, rilis=?, skor=?, sinopsis=?, serial=? WHERE idmovie=$idmovie";
    $hasil = $koneksi->prepare($sql);
    $hasil->bind_param("ssdsi", $judul, $rilis, $skor, $sinopsis, $serial);
    $hasil->execute();

    if ($hasil->error != true) {
        echo "Update Success : " . $hasil->affected_rows . "<br>";
        //$idmovie = $hasil->insert_id;
        //echo "Movie ID Baru : " . $idmovie;

        $sql = "DELETE FROM genre_movie WHERE idmovie=$idmovie";
        $hasil = $koneksi->prepare($sql);
        $hasil->execute();

        $sql = "INSERT INTO genre_movie VALUES (?,?)";
        $hasil = $koneksi->prepare($sql);
        $hasil->bind_param("ii", $idmovie, $idgenre);

        foreach ($genre as $idgenre) {
            $hasil->execute();
        }


        // Delete gambar
        if (isset($_POST['gambar_dihapus'])) {
            $sql = 'DELETE FROM gambar where idgambar = ?';
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param('i', $idgambar);
            foreach ($_POST['gambar_dihapus'] as $nama_file) {
                $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
                $idgambar = trim($nama_file, '.' . $ext);
                $stmt->execute();
                if (file_exists("gambar/$idgambar.$ext")) {
                    unlink("gambar/$idgambar.$ext");
                }
            }
        }

        // 1.dapatkan ext gambar
        // 2.insert ext ke tabel gambar berdasarkan idmovie tertentu
        // 3.dapatkan idgambar
        // 4.pindahkan gambar ke folder gambar, dan rename gambar tersebut berdasarkan idgambarnya
        // contoh : idgambar =5, nama file = 5.jpg/5.png

        // $ext = substr($gambar['name'], -3);

        for ($i = 0; $i < count($gambar['name']); $i++) {
            $ext = pathinfo($gambar['name'][$i], PATHINFO_EXTENSION);
            $sql = "INSERT INTO gambar (extention,idmovie) VALUES (?,?)";
            $stmt = $koneksi->prepare($sql);
            if ($gambar['name'][$i] != "") {
                $stmt->bind_param("si", $ext, $idmovie);
                $stmt->execute();
                $idgambar = $stmt->insert_id;
                move_uploaded_file($gambar['tmp_name'][$i], "gambar/$idgambar.$ext");
            } else {
                // jika user tidak upload gambar
                // beri pesan error atau abaikan
            }
        }



        // $sql = "UPDATE movie SET extention=? WHERE idmovie=$idmovie";
        // $hasil = $koneksi->prepare($sql);
        // $hasil->bind_param("s", $ext);
        // $hasil->execute();
    } else
        echo "Insert Failed : " . $koneksi->error;

    $koneksi->close();
    ?>
    <br>
    <a href="index.php">Back</a>
</body>

</html>