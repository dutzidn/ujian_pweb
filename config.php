<?php
define('DB_SERVER', 'localhost'); //Alamat server database
define('DB_USERNAME', 'root'); //username database
define('DB_PASSWORD', ''); //password
define('DB_NAME', 'pangsit_datacenter'); //nama database yang digunakan

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if($link === false){ //Error handling ketika tidak bisa tersambung ke database
    die("ERROR: Could not connect. " . mysqli_connect_error()); 
}
?>