<div class="container allaround">
    <div id="coupons_container">
        <div class="container">
            <h1>
                <?php echo LOGIN_TEXT; ?>
            </h1>
            <div id="loginbox">
                <p>
                    <?php //echo LOGIN_SUBTITLE; ?>
                </p>
                <div id="message">
                    <?php echo $user_info; ?>
                </div>
                <form id="formlogin" class="form-inline text-left" name="formlogin" role="form" action="userstatus.php" method="post">
                    <div class="row">
                        <label for="msisdn" class="control-label text-right sr-only"><?php echo LOGIN_USERNAME; ?>: </label>
                        <input class="form-control" type="text" name="msisdn" id="msisdn" placeholder="<?php echo LOGIN_USERNAME; ?>" />
                    </div>

<!--
                    <div class="row">
                        <label for="vercode" class="control-label text-right sr-only"><?php // echo LOGIN_CAPTCHA; ?>: </label>
                        <input class="form-control" type="text" name="vercode" id="vercode" placeholder="<?php // echo LOGIN_CAPTCHA; ?>" />
                    </div>
-->
<!--                    <div class="row text-center"> <img src="php/captchaimage.php?width=175&amp;height=40&amp;characters=6" id="vercodeimage" /> </div>-->
                    <div class="row text-center">
                        <button type="submit" id="login" name="login" value="login" class="btn btn-info"><?php echo LOGIN_LABEL;?></button>
                        <input type="hidden" name="ajax_action" id="ajax_action" value="login" />
                    </div>
                    <div class="g-recaptcha" data-size="compact" data-sitekey="6Lelx0MUAAAAAGJQGtD44s9aEhhQKnp1Bv87ZNRJ"></div>
                </form>
            </div>
            <?php echo LOGIN_SUBLINK; ?>
            <div class="embed-responsive embed-responsive-16by9">

            </div>
        </div>
    </div>
</div>
