<!DOCTYPE html>
<html>

<head>
    <title>FSP - Database</title>
    <style type="text/css">
        .form-group {
            clear: both;
            padding: 2px;
        }

        .form-label {
            display: block;
            float: left;
            width: 20%;
        }

        div#file_upload {
            float: left;
        }

        div#file_upload input {
            display: block;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <form method="POST" enctype="multipart/form-data" action="insertproses.php">
        <div class="form-group">
            <label class="form-label">Judul</label>
            <input type="text" name="judul">
        </div>
        <div class="form-group">
            <label class="form-label">Rilis</label>
            <input type="date" name="rilis">
        </div>
        <div class="form-group">
            <label class="form-label">Skor</label>
            <input type="text" name="skor">
        </div>
        <div class="form-group">
            <label class="form-label">Sinopsis</label>
            <input type="text" name="sinopsis">
        </div>
        <div class="form-group">
            <label class="form-label">Serial</label>
            <input type="radio" name="serial" value="1">Ya
            <input type="radio" name="serial" value="0">Tidak
        </div>
        <!-- combobox genre -->
        <div class="form-group">
            <label class="form-label">Genre</label>
            <?php
            $koneksi = new mysqli("localhost", "root", "", "my_schema");

            $sql = "SELECT * FROM genre";
            $hasil = $koneksi->query($sql);

            while ($baris = $hasil->fetch_assoc()) {
                $id = $baris['idgenre'];
                $nama = $baris['nama'];
                echo "<input type='checkbox' name='genre[]' value='$id'>$nama";
            }

            $koneksi->close();
            ?>

        </div>
        <!-- end combobox -->
        <div class="form-group">
            <label class="form-label">Gambar</label>
            <div id="file_upload">
                <input type="file" name="gambar[]">
            </div>
        </div>
        <div class="form-group">
            <button type="button" id="tambahgambar">Tambah Gambar</button>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="Tambah Movie">
        </div>
    </form>
</body>
<script src="jquery-3.5.1.min.js"></script>
<script>
    $('#tambahgambar').click(function() {
        $('#file_upload').append('<input type="file" name="gambar[]">');
    })
</script>

</html>