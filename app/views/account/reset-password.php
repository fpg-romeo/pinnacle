<div class="row">
    <div class="col-md-12">
        <?php flash(promptMessage('message')); ?>
    </div>
</div>
<form id="resetPasswordForm" method="post">
    <div class="form-group">
        <input name="password" type="password" id="password" class="form-control fc-outline-dark" placeholder="Enter your new password" value="" required>
        <?php echo multiArrayKeyExist($data, 'error', 'password'); ?> 
        <div id="error-message-password" class="error-message" hidden></div>
    </div>
    <div class="form-group">
        <input name="confirm_password" type="password" id="confirm_password" class="form-control fc-outline-dark" placeholder="Confirm new password" value="" required>
        <?php echo multiArrayKeyExist($data, 'error', 'confirm_password'); ?> 
        <div id="error-message-confirm_password" class="error-message" hidden></div>
    </div>
    <!-- <button name="submit" type="submit" onclick="return beforeSubmit();" class="btn btn-info btn-block">Change Password</button> -->
    <input type="hidden" name="submit_form" value="1">
    <button class="g-recaptcha btn btn-info btn-block " 
    data-sitekey="<?php echo multiKeyExists($data, 'site_key') ?>" 
    data-callback='resetPasswordSubmit' 
    data-action='submit'
    onclick="return beforeSubmit();"
    >Change Password</button>
</form>


<script type="text/javascript">
    
    function beforeSubmit(){

        //validatePassword  

        if ( $('#password').val() == '' ) {
            $('#error-message-password').html('<p class="required-prompt">*This field is required.</p>').attr('hidden',false);
            return false;
        }
        if ( $('#confirm_password').val() == '' ) {
            $('#error-message-confirm_password').html('<p class="required-prompt">*This field is required.</p>').attr('hidden',false);
            return false;
        }else{

            if ( $('#password').val() != $('#confirm_password').val() ) {
                $('#error-message-confirm_password').html('<p class="required-prompt">*Password mismatch.</p>').attr('hidden',false);
                return false;
            }else{
                if ( !confirm("Proceed changing password?") ) {
                    return false;
                }else{
                    return true;
                }
            }
        }

    }
</script>

<script type="text/javascript">
    function resetPasswordSubmit(token) {
        document.getElementById("resetPasswordForm").submit();
    }
</script>