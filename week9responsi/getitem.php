<?php 
$mysqli = new mysqli('localhost','root','','fsp-b');

$offset = $_POST['offset'];

$sql = "SELECT judul FROM movie ORDER BY judul ASC LIMIT $offset,3";
$result = $mysqli->query($sql);

$movies = [];

while ($row = $result->fetch_assoc()) {
    $movies[] = $row;   
}
$mysqli->close();
echo json_encode($movies);