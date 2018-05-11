<?php
session_start();
if(isset($_GET['file_id']) && isset($_GET['image'])) {
    include_once("functions.php");
    $obj = new personal_assistant();
    $file = $obj->getFileById($_GET['file_id']);
    if($file && file_exists(str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $file['file_url']) && isset($_SESSION['isLoggedIn'])) {
        header('Content-Type: '. $file['file_type']);
        echo file_get_contents(str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $file['file_url']);
        exit;
    } else if($file && file_exists(str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $file['file_url']) && $file['access_type'] === "Public") {
        header('Content-Type: '. $file['file_type']);
        echo file_get_contents(str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $file['file_url']);
        exit;
    } else {
        echo "Access denied!";
    }
} else if(isset($_GET['file_id'])) {
    include_once("functions.php");
    $obj = new personal_assistant();
    $file = $obj->getFileById($_GET['file_id']);
    if($file && file_exists(str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $file['file_url'])) {
        if($file['access_type'] === "Public") {
            header('Content-Disposition: attachment; filename="'.basename($file['file_name']).'"');
            header('Content-Type: '. $file['file_type']);
            header('Content-Length: ' . str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $file['file_url']);
            readfile(str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $file['file_url']);
            exit;
        } else if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true && $file['fid'] === $_SESSION['user_id']) {
            header('Content-Disposition: attachment; filename="'.basename($file['file_name']).'"');
            header('Content-Type: '. $file['file_type']);
            header('Content-Length: ' . str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $file['file_url']);
            readfile(str_replace("\\", "/", realpath(__DIR__ . '/..')) ."/content_data/uploads/". $file['file_url']);
            exit;
        } else {
            echo "Access denied!";
        }
    } else {
        echo "Access denied!";
    }
} else {
    echo "Access denied!";
}
?>