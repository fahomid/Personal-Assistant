<?php
    if(!isset($page_type)) $page_type = "basic";
    if($page_type === "index") include_once("lib/functions.php");
    else include_once("../lib/functions.php");
    $obj = new personal_assistant();
    if($page_type === "dashboard") {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?php echo $obj->base_url; ?>img/favicon.ico">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Your Personal Web Assistant - 77beats.com</title>

        <link href="<?php echo $obj->base_url; ?>css/style.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="<?php echo $obj->base_url; ?>css/bootstrap.min.css" rel="stylesheet">
        <!-- Font-awesome -->
        <link href="<?php echo $obj->base_url; ?>css/font-awesome.min.css" rel="stylesheet">
        <!-- Google font -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body style="overflow-x: hidden; overflow-y: auto;">
<?php
    } else {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?php echo $obj->base_url; ?>img/favicon.ico">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>Your Personal Web Assistant - 77beats.com</title>

        <link href="<?php echo $obj->base_url; ?>css/style.css" rel="stylesheet">
        <!-- Bootstrap -->
        <link href="<?php echo $obj->base_url; ?>css/bootstrap.min.css" rel="stylesheet">
        <!-- Font-awesome -->
        <link href="<?php echo $obj->base_url; ?>css/font-awesome.min.css" rel="stylesheet">
        <!-- Google font -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <header class="header clearfix">
            <div class="container-fluid">
                <div class="logo_container">
                    <img src="<?php echo $obj->base_url; ?>img/logo.png" alt="77beats.com" />
                </div>
                <div class="nav_container">
                    <ul>
                        <?php if($page_type === "login" || $page_type === "signup") { ?>
                        <li><a href="<?php echo $obj->base_url; ?>">Home</a></li>
                        <li><a href="<?php echo $obj->base_url; ?>signup/">Sign Up</a></li>
                        <?php } else { ?>
                        <li class="active"><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="#contact">Contact Us</a></li>
                        <?php
                            if($obj->userLoggedIn()) {
                        ?>
                        <li><a href="<?php echo $obj->base_url; ?>dashboard/">Dashboard</a></li>
                        <li><a href="<?php echo $obj->base_url; ?>logout/">Logout</a></li>
                        <?php  } else {
                        ?>
                        <li><a href="<?php echo $obj->base_url; ?>login/">Login</a></li>
                        <li><a href="<?php echo $obj->base_url; ?>signup/">Sign Up</a></li>
                        <?php } } ?>
                    </ul>
                </div>
            </div>
        </header>
    <?php } ?>