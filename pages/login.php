<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$page_type = "login";
include_once("../includes/header.php");
if(isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true) header("Location: ". $obj->base_url ."dashboard/");
?>
        <div class="login_sign_up_container">
            <div class="container">
                <div class="login_form_container">
                    <div class="bg_wait" style="background-image: url(<?php echo $obj->base_url; ?>/img/ajax-loader.gif); display: none;"></div>
                    <p class="form_heading">Log in to get on</p>
                    <div class="data_response">
                        <?php
                        if(isset($_GET['error'])) echo '<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>Error! '. $_GET['error'] .'!</strong></div>';
                        else if(isset($_GET['msg'])) echo '<div class="alert alert-info alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a><strong>'. $_GET['msg'] .'</strong></div>';

                        ?>
                    </div>
                    <form class="login_form" method="post">
                        <input type="text" class="form-control" id="email" placeholder="Email Address">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="remember_login" name="remember_login" value="remember_login"> Remember me
                            </label>
                        </div>
                        <button type="submit" class="btn x_l_s_btn">Login Now</button>
                        <div class="m_link_container">
                            <p><a href="<?php echo $obj->base_url; ?>forget_password/">Forgot password?</a></p>
                            <p><a href="<?php echo $obj->base_url; ?>signup/">Don't have an account?</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script> var security_token = "<?php echo $_SESSION['csrf_token']; ?>"; </script>
<?php include_once("../includes/footer.php"); ?>