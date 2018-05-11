<?php
session_start();
setcookie("user_token", "", time() - 3600, "/");
setcookie("secure_hash", "", time() - 3600, "/");
include_once("../lib/functions.php");
$obj = new personal_assistant();
if(isset($_SESSION['isLoggedIn']) && isset($_COOKIE['user_token']) && isset($_COOKIE['secure_hash'])) {
    $obj->logoutUserSession($_COOKIE['user_token'], $_COOKIE['secure_hash']);
}
session_unset();
session_destroy();
$r_url = $obj->base_url;
if(isset($_GET['deactivate'])) {
    $r_url = $r_url ."login/?msg=Account deactivated successfully!"; 
} else {
    $r_url = $r_url ."login/?msg=Logged out successfully!";
}
header("Location: $r_url");
?>