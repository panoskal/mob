<?php

if (isset($_POST['action']) && $_POST['action'] === 'login') {

    $success = true;

    if (empty($_POST['username'])) {
        $loginErrMsg['usernameErr'] = "Please enter your username";
        $success = false;
    } else {
        $username = Sanitize::processString($_POST['username']);
    }

    if (empty($_POST['password'])) {
        $loginErrMsg['empty_password'] = "Please enter your password";
        $success = false;
    } else {
        $password = $_POST['password'];
    }

    if ($success) {
        $userAuth = User::userAuth($username);
        if ($userAuth && Sanitize::passVerify($password, $userAuth->password) ) {

            $session->login($userAuth);

        } else {
            $loginErrMsg['wrong_creds'] = "Wrong Username/Password";
        }
    }

} else {
    $username = "";
    $password = "";
}

?>
   <section id="loginpage">
    <div class="loginform">
        <form action="" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="login-input form-control" id="username" placeholder="&#xf007; Username">
            </div>
            <div class="form-group">
               <label for="password">Password</label>
                <input type="password" name="password" class="login-input form-control" id="password" placeholder="&#xf023; Password">
            </div>
            <button type="submit" name="login" id="login" class="button-faux" data-configid>Login</button>
<!--            <input type="submit" name="login" class="btn btn-large button-faux" value="Login">-->
            <input type="hidden" name="action" value="login">
        </form>
        <div class="login-err-msg">
        <?php
            if (!empty($loginErrMsg) && is_array($loginErrMsg)) {
                foreach ($loginErrMsg as $msg) {
                    echo $msg . '<br>';
                }
            }
            if ($session->is_logged_in()) {
                Header('Location: '.$_SERVER['PHP_SELF']);
                Exit(); //optional
            }
            ?>
        </div>
    </div>
</section>
