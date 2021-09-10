<!DOCTYPE html>
<html>

<head>
    <title>Demo doang</title>
    <style type="text/css">
        table,
        td,
        th {
            border: 1px solid black;
        }

        ul {
            list-style-type: none;
        }

        li:hover {
            background-color: #e6e4e4;
        }

        li {
            display: inline-block;
            padding: 6px 10px;
            border: 1px solid #dee2e6;
            background-color: #fff;
        }

        li a {
            text-decoration: none;
            color: #0d6efd;
        }

        li.active {
            background-color: #0d6efd;
            color: #dee2e6;
        }

        li:last-child {
            border-radius: 0 5px 5px 0;
        }

        li:first-child {
            border-radius: 5px 0 0 5px;
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
    $jumdata = $hasil->num_rows;
    $perpage = (isset($_GET['datapage'])) ? $_GET['datapage'] : 2;

    //$baris = $hasil->fetch_assoc();
    //echo $baris["judul"]."<BR>";
    ?>
    <form method="get" action="">
        Keyword: <input type="text" name="judul" value="<?= $judul ?>" /> |
        Data Page:
        <select name="datapage">
            <option value="2" <?php if ($perpage == 2) echo "selected"; ?>>2</option>
            <option value="3" <?php if ($perpage == 3) echo "selected"; ?>>3</option>
            <option value="4" <?php if ($perpage == 4) echo "selected"; ?>>4</option>
            <option value="5" <?php if ($perpage == 5) echo "selected"; ?>>5</option>
        </select>
        <input type="submit" name="submit" value="Apply">
    </form>
    <br>
    <?php
    $p = (isset($_GET['p'])) ? $_GET['p'] : 1;
    $jumpage = ceil($jumdata / $perpage); //10
    //echo "$perpage $jumdata $jumpage";

    //cek apakah jumlah page yg ingin kita tampilkan kurang dari jumlahpage
    if ($jumpage <= 5) {
        $firstPage = 1;
        $lastPage = $jumpage;
    }
    // jumlahpage lebih dr page yg ingin kita tampilkan    
    else {
        // halaman saat ini kurang dari 3, first page last page nya sama
        if ($p <= 3) {
            $firstPage = 1;
            $lastPage = 5;
        }
        // cek apakah halaman selanjutnya melebihi jumlah page
        else if ($p + 1 >= $jumpage) {
            $lastPage = $jumpage;
            $firstPage = $jumpage - 4;
        }
        // halaman saat ini lebih dari 3
        else {
            $firstPage = $p - 2;
            $lastPage = $p + 2;
        }
    }

    $awal = ($p - 1) * $perpage; //offset = halaman saat ini - 1 * data per page
    if (isset($_GET['orderby'])) {
        $orderby = $_GET['orderby'];
        $type = $_GET['type'];
        $sql = "SELECT * FROM movie WHERE judul LIKE '%$judul%' ORDER BY $orderby $type LIMIT $awal,$perpage";
    } else {
        $sql = "SELECT * FROM movie WHERE judul LIKE '%$judul%' LIMIT $awal,$perpage";
    }
    echo "<ul>";
    $next = $p + 1;
    $prev = $p - 1;
    if ($p > 1) {
        if (isset($_GET['orderby']))
            echo "<li class='prev'><a href='?judul=$judul&datapage=$perpage&p=$prev&orderby=judul&type=$type' >Prev</a></li>";
        else
            echo "<li class='prev'><a href='?judul=$judul&datapage=$perpage&p=$prev' >Prev</a></li>";
    }
    for ($i = $firstPage; $i <= $lastPage; $i++) {
        if ($p == $i) {
            echo "<li class='active'>$i</li>";
        } else {
            if (isset($_GET['orderby']))
                echo "<li class='prev'><a href='?judul=$judul&datapage=$perpage&p=$i&orderby=judul&type=$type'>$i</a></li>";
            else
                echo "<li><a href='?judul=$judul&datapage=$perpage&p=$i'>$i</a></li>";
        }
    }
    if ($p < $jumpage) {
        if (isset($_GET['orderby']))
            echo "<li class='next'><a href='?judul=$judul&datapage=$perpage&p=$next&orderby=judul&type=$type' >Next</a></li>";
        else
            echo "<li class='next'><a href='?judul=$judul&datapage=$perpage&p=$next' >Next</a></li>";
    }
    echo "</ul>";

    if (isset($_GET['type'])) {
        $type = $_GET['type'];
        if ($type == 'asc') {
            $type = 'desc';
        } else {
            $type = 'asc';
        }
    } else {
        $type = 'asc';
    }

    ?>
    <table>
        <tr>
            <th>Gambar</th>
            <th><a href=<?= "?judul=$judul&datapage=$perpage&orderby=judul&type=$type" ?>>Judul</a></th>
            <th><a href=<?= "?judul=$judul&datapage=$perpage&orderby=rilis&type=$type" ?>>Tgl Rilis</a></th>
            <th><a href=<?= "?judul=$judul&datapage=$perpage&orderby=serial&type=$type" ?>>Serial</a></th>
            <th>Genre</th>
            <th>Aksi</th>
        </tr>

        <?php
        $hasil = $koneksi->query($sql);
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