<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
include_once("../lib/functions.php");
if(isset($_SESSION['isLoggedIn'])) {
        $page_type = "dashboard";
        include_once("../includes/header.php");
        include_once("../modules/dashboard.php");
        include_once("../includes/footer.php");
} else if(isset($_COOKIE['user_token']) && isset($_COOKIE['secure_hash'])) {
    $obj = new personal_assistant();
    if($result = $obj->validateSecurityToken($_COOKIE['user_token'], $_COOKIE['secure_hash'])) {
        $_SESSION['isLoggedIn'] = true;
        $_SESSION['first_name'] = $result['first_name'];
        $_SESSION['last_name'] = $result['last_name'];
        $_SESSION['email'] = $result['email'];
        $_SESSION['type'] = $result['type'];
        $_SESSION['type'] = $result['type'];
        $_SESSION['joined_at'] = $result['joined_at'];
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['image'] = $result['image_url'];
        $_SESSION['account_status'] = $result['account_status'];
        $page_type = "dashboard";
        include_once("../includes/header.php");
        include_once("../modules/dashboard.php");
        include_once("../includes/footer.php");
    } else {
        $obj = new personal_assistant();
        header("Location: ". $obj->base_url ."login/?error=Please login first to access your dashboard!");
    }
} else {
    $obj = new personal_assistant();
    header("Location: ". $obj->base_url ."login/?error=Please login first to access your dashboard!");
}
?>