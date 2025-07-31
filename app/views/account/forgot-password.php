<div class="row">
    <div class="col-md-12">
        <?php flash(promptMessage('message')); ?>
    </div>
</div>
<form id="forgotPasswordForm" method="post">
    <div class="form-group">
        <input name="email" type="text" class="form-control fc-outline-dark" placeholder="Enter your email" value="<?php echo multiArrayKeyExist($data, 'post', 'email'); ?>">
        <?php echo multiArrayKeyExist($data, 'error', 'email'); ?> 
        <small>Enter your recovery email for account verification.</small>
        <a href="/login" class="tx-info tx-12 d-block mg-t-10">Back to Login</a>
    </div>
    <!-- <button name="submit" type="submit" class="btn btn-info btn-block">Verify Account</button> -->
    <input type="hidden" name="submit_form" value="1">
    <button class="g-recaptcha btn btn-info btn-block " 
    data-sitekey="<?php echo multiKeyExists($data, 'site_key') ?>" 
    data-callback='forgotPasswordSubmit' 
    data-action='submit'>Verify Account</button>
</form>

<script type="text/javascript">
    function forgotPasswordSubmit(token) {
        document.getElementById("forgotPasswordForm").submit();
    }
</script>