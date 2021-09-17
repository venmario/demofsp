<?php
require_once('parent.php');

class User extends ParentClass
{
    public function __construct($server, $user, $pass, $db)
    {
        parent::__construct($server, $user, $pass, $db);
    }

    public function GenerateSalt()
    {
        return substr(session_id(), 0, 5); //Generate 5 String random 
    }

    public function GetSaltedPassword($password, $salt)
    {
        $saltedPassword = md5(md5($password) . $salt);
        return $saltedPassword;
    }

    public function Register($iduser, $fullname, $password)
    {
        $salt = $this->GenerateSalt();
        $saltedPassword = $this->GetSaltedPassword($password, $salt);

        $sql = 'INSERT INTO users (iduser,nama,password,salt) VALUES (?,?,?,?)';
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ssss", $iduser, $fullname, $saltedPassword, $salt);
        $stmt->execute();

        return $stmt->affected_rows; //jumlah baris yang terpengaruh
    }

    public function GetSalt($iduser)
    {
        $sql = "select salt from users where iduser = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $iduser);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $salt = "";
        if ($row != false) {
            $salt = $row['salt'];
        }
        return $salt;
    }

    public function DoLogin($iduser, $password)
    {
        date_default_timezone_set("Asia/Jakarta"); //set Default timezone

        $salt = $this->GetSalt($iduser);
        $saltedPwd = $this->GetSaltedPassword($password, $salt);

        $sql = "SELECT * FROM users WHERE iduser = ? AND password = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("ss", $iduser, $saltedPwd);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();

        if ($row != false) { //iduser dan password benar
            $now = strtotime('now'); //waktu sekarang dlm bentuk milidetik
            $next_login = strtotime($row['next_login']); // waktu next login dlm bentuk milidetik
            if ($now > $next_login) { //cek apakah waktu sekarang sudah melewati next login
                $_SESSION['username'] = $iduser;
                $_SESSION['nama'] = $row['nama'];
                //set error jadi 0 dan next_login jadi null
                $this->mysqli->query("UPDATE users SET error = 0, next_login = null WHERE iduser = '$iduser'");
            } else { //waktu sekarang blm melewati next login
                $_SESSION['login'] = "next login " . $row['next_login'];
            }
            return 1;
        } else { // password salah
            $sql = 'SELECT error FROM users WHERE iduser = ?';
            $stmt = $this->mysqli->prepare($sql);
            $stmt->bind_param("s", $iduser);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res->fetch_assoc();
            if ($row != false) {
                $error = $row['error']; //ambil jumlah error
                $error = $error + 1; // error ditambah 1

                if ($error < 3) { // jika error kurang dari 3 hanya update jumlah error
                    $this->mysqli->query("UPDATE users SET error = $error WHERE iduser = '$iduser'");
                } else { // jika error lebih dari sama dengan 3 , update error dan next login (waktu skrg ditambah 10 menit)
                    $timestamp = strtotime('now + 10 minutes'); //waktu sekarang ditambah 10 menit dlm bentuk milidetik
                    $next_login = date("Y-m-d H:i:sa", $timestamp); //ubah waktu dlm milidetik ke format date
                    $this->mysqli->query("UPDATE users SET error = $error, next_login = '$next_login' WHERE iduser = '$iduser'");
                }
            }

            return "salah";
        }
    }
}
