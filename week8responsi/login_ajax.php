<?php

$koneksi = new mysqli('localhost', 'root', '', 'fsp-b');

$username = $_POST['uname'];
$password = $_POST['passwd'];

$sql = 'select * from users where iduser = ? and password = ?';
$stmt = $koneksi->prepare($sql);
$stmt->bind_param('ss', $username, $password);
$stmt->execute();
$res = $stmt->get_result();

// if ($res->num_rows > 0) {
//     echo 'success';
// } else {
//     echo 'failed';
// }

if ($res->num_rows > 0) {
    $row = $res->fetch_assoc();
    echo json_encode($row);
} else {
    echo 'failed';
}
