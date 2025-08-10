<?php
session_start();
include '../core/function.php';
$url->run();

checkIsLogin();
//print_r (getTitle());

//$conn = mysqli_connect("localhost","root","","ukk_spp");

//$password = password_hash('admin', PASSWORD_DEFAULT);

//mysqli_query($conn,"INSERT INTO users( user_name, password, level) VALUES ('admin', '$password', 1)");