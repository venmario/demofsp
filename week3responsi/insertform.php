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
            ?>

        </div>
        <!-- end combobox -->
        <div class="form-group">
            <label class="form-label">Gambar</label>
            <div id=file_upload>
                <input type="file" name="gambar[]">
            </div>
        </div>
        <div class="form-group">
            <button type="button" id="tambahgambar">Tambah Gambar</button>
        </div>

        <div class="form-group">
            <label class="form-label">Pemain</label>
            <select name="peran" id="peran">
                <option value="Utama">Utama</option>
                <option value="Pembantu">Pembantu</option>
                <option value="Cameo">Cameo</option>
            </select>
            <select name="pemain" id="pemain">
                <?php
                $sql = "SELECT * FROM pemain";
                $hasil = $koneksi->query($sql);

                while ($baris = $hasil->fetch_assoc()) {
                    $id = $baris['idpemain'];
                    $nama = $baris['nama'];
                    echo "<option value='$id'>$nama</option>";
                }

                $koneksi->close();
                ?>
            </select>
            <button type="button" id="tambahpemain">Tambah Pemain</button>
        </div>
        <div class="form-group">
            <label class="form-label">Daftar Pemain</label>
            <table id="daftarpemain" width="50%">
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Peran</th>
                    <th>Action</th>
                </tr>
            </table>
        </div>
        <div class="form-group">
            <input type="submit" name="submit" value="Tambah Movie">
        </div>
    </form>
    <script src="jquery-3.5.1.min.js"></script>
    <script>
        $('#tambahgambar').click(function() {
            $('#file_upload').append('<input type="file" name="gambar[]">');
        });

        $('#tambahpemain').click(function() {
            var peran = $('#peran').val();
            var idpemain = $('#pemain').val();
            var nmpemain = $('#pemain option:selected').text();

            str = '<tr>';
            str = str + '<td>' + idpemain + '</td>';
            str = str + '<td>' + nmpemain + '</td>';
            str = str + '<td>' + peran + '</td>';
            str = str + '<td><input type="hidden" name="lpemain[]" value="' + idpemain + '"><input type="hidden" name="lperan[]" value="' + peran + '"><button type="button" class="hapuspemain">Hapus</td>';
            str = str + '</tr>';
            $('#daftarpemain').append(str);
        });

        //$('.hapuspemain').click(function() {
        $('body').on('click', '.hapuspemain', function() {
            $(this).parent().parent().remove();
        });
    </script>
</body>

</html>