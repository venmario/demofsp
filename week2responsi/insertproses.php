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

    $judul = $_POST['judul'];
    $rilis = $_POST['rilis'];
    $skor = $_POST['skor'];
    $sinopsis = $_POST['sinopsis'];
    $serial = $_POST['serial'];
    $genre = $_POST['genre'];
    $gambar = $_FILES['gambar'];

    // echo "<pre>";
    // echo count($gambar['name']);
    // echo "</pre>";

    //$sql = "INSERT INTO movie VALUES(null,'$judul','$rilis',$skor,'sinopis movie nya',1,'jpg','genre nya')";
    //$hasil = $koneksi->query($sql);

    $sql = "INSERT INTO movie(judul,rilis,skor,sinopsis,serial) VALUES (?,?,?,?,?)";
    $hasil = $koneksi->prepare($sql);
    $hasil->bind_param("ssdsi", $judul, $rilis, $skor, $sinopsis, $serial);
    $hasil->execute();

    if ($hasil->error != true) {
        echo "Insert Success : " . $hasil->affected_rows . "<br>";
        $idmovie = $hasil->insert_id;
        echo "Movie ID Baru : " . $idmovie;

        $sql = "INSERT INTO genre_movie VALUES (?,?)";
        $hasil = $koneksi->prepare($sql);

        foreach ($genre as $idgenre) {
            $hasil->bind_param("ii", $idmovie, $idgenre);
            $hasil->execute();
        }

        //1. dapatkan ext gambar
        //2. insert ext ke tabel gambar berdasarkan idmovie tertentu
        //3. dapatkan idgambar
        //4. pindahkan gambar ke folder gambar, dan rename gambar tersebut berdasarkan idgambarnya
        // contoh: idgambar = 5, nama file gambar = 5.jpg/5.png

        for ($i = 0; $i < count($gambar['name']); $i++) {
            if ($gambar['name'][$i] != "") {
                // $ext = substr($gambar['name'], -3);
                $ext = pathinfo($gambar['name'][$i], PATHINFO_EXTENSION);
                $sql = "INSERT INTO gambar (extention,idmovie) VALUES (?,?)";
                $stmt = $koneksi->prepare($sql);
                $stmt->bind_param('si', $ext, $idmovie);
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