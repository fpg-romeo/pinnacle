<div class="row">
    <div class="col-md-12">
        <?php flash(promptMessage('message')); ?>
    </div>
</div>
<?php /*
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
*/ ?>



<form id="loginForm" method="post">
    <div class="mb-6 form-control-validation">
        <label for="email" class="form-label">Email or Username</label>
        <input type="text" class="form-control" id="email" name="username" placeholder="Enter your email or username" autofocus />
    </div>
    <div class="mb-6 form-password-toggle form-control-validation">
        <label class="form-label" for="password">Password</label>
        <div class="input-group input-group-merge">
            <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
            <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>
        </div>
    </div>
    <div class="my-8">
        <div class="d-flex justify-content-between">
            <div class="form-check mb-0 ms-2">
                <input class="form-check-input" type="checkbox" id="remember-me" />
                <label class="form-check-label" for="remember-me"> Remember Me </label>
            </div>
            <a href="auth-forgot-password-basic.html">
                <p class="mb-0">Forgot Password?</p>
            </a>
        </div>
    </div>
    <div class="mb-6">
        <!-- <button class="btn btn-primary d-grid w-100" type="submit">Login</button> -->
        <input type="hidden" name="submit_form" value="1">
        <button class="g-recaptcha btn btn-info btn-block mg-b-10" data-sitekey="<?php echo multiKeyExists($data, 'site_key') ?>" data-callback='loginSubmit' data-action='submit' type="submit">Sign In</button>
    </div>
</form>
<p class="text-center">
    <span>New on our platform?</span>
    <a href="auth-register-basic.html">
        <span>Create an account</span>
    </a>
</p>
<script type="text/javascript">
    function loginSubmit(token) {
        document.getElementById("loginForm").submit();
    }
</script>