<div class="row">
    <div class="col-md-12">
        <?php flash(promptMessage('message')); ?>
    </div>
</div>
<form id="loginForm" method="post">
    <div class="form-group">
        <input name="username" type="text" class="form-control fc-outline-dark" placeholder="Enter your email/username" value="<?php echo multiArrayKeyExist($data, 'post', 'username'); ?>">
        <?php echo multiArrayKeyExist($data, 'error', 'username'); ?> 
    </div>
    <div class="form-group">
        <input name="password" type="password" class="form-control fc-outline-dark" placeholder="Enter your password">
        <?php echo multiArrayKeyExist($data, 'error', 'password'); ?> 
    </div>
    <div class="form-group mg-t-0 mg-b-30 pd-l-2">
        <label class="ckbox tx-gray-500">
            <input name="remember_me" type="checkbox" checked >
            <span>Remember Me</span>
        </label>
    </div>
    <!-- <button name="submit" type="submit" class="btn btn-info btn-block mg-b-10">Sign In</button> -->
    <input type="hidden" name="submit_form" value="1">
    <button class="g-recaptcha btn btn-info btn-block mg-b-10" data-sitekey="<?php echo multiKeyExists($data, 'site_key') ?>" data-callback='loginSubmit' data-action='submit'>Sign In</button>
    <div class="text-center">OR</div>
    <button name='google-signin' type='submit' class="btn btn-google btn-with-icon btn-block mg-t-10">
        <div class="ht-50">
            <span class="google-icon-wrapper">
                <div class="wd-50">
                    <img src="/public/img/button-google.png" width="25">
                </div>
            </span>
            <span class="pd-x-15 mg-l-5 btn-google-text">Sign In with Google</span>
        </div>
    </button>
    <div class="form-group mg-t-30">
        <a href="/forgot-password" class="tx-info d-block mg-t-10 tx-bold tx-underline"><u>Forgot password?</u></a>
    </div>
</form>
<!--
<hr>
<div class="mg-t-20 tx-gray-600">
    <p>Need help? Please contact us:</p>
    <p>
        Email: <a href="mailto:support@oom.com.sg" class="tx-gray-600">support@oom.com.sg</a>
        <br>
        Office: +65 6391 0930
    </p>
</div>
-->

<script type="text/javascript">
    function loginSubmit(token) {
        document.getElementById("loginForm").submit();
    }
</script>