<?php
$page_type = "signup";
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
include_once("../includes/header.php"); ?>
        <div class="login_sign_up_container">
            <div class="container">
                <div class="signup_form_container">
                    <div class="bg_wait" style="background-image: url(<?php echo $obj->base_url; ?>/img/ajax-loader.gif); display: none;"></div>
                    <p class="form_heading">Sign up to join us</p>
                    <div class="data_response"></div>
                    <form class="signup_form" method="post">
                        <input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name">
                        <input type="text" name="last_name" class="form-control" id="last_name" placeholder="Last Name">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Email Address">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="agree_terms" name="agree_terms"> I agree to the <a href="<?php echo $obj->base_url; ?>/terms/">77beats Terms</a>
                            </label>
                        </div>
                        <button type="submit" class="btn x_l_s_btn sign_up_button">Sign Up Now</button>
                        <div class="m_link_container">
                            <p><a href="<?php echo $obj->base_url; ?>login/">Already have an account?</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script> var security_token = "<?php echo $_SESSION['csrf_token']; ?>"; </script>
<?php include_once("../includes/footer.php"); ?>