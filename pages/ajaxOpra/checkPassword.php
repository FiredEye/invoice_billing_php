<?php
session_start();
$password_check = password_verify($_POST['password'], $_SESSION['password']);
if ($password_check) {
    echo 1;
}
