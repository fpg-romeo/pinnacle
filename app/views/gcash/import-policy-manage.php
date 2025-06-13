    <form id="form-policy" method="post"> 
        <h6 class="mg-b-0 tx-uppercase tx-primary tx-bold modal-title">MANAGE RECORD</h6>
        <hr>
        <div class="row mg-t-40 mg-b-20">
            <label class="col-sm-4 form-control-label">First Name<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="first_name" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'first_name'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Middle Name<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="middle_name" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'middle_name'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Last Name<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="last_name" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'last_name'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Date of Birth<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="date_of_birth" type="text" class="form-control calendar-option" value="<?php echo multiArrayKeyExist($data, 'policy', 'date_of_birth'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Mobile Number<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="mobile_number" type="text" class="form-control numeric" value="<?php echo multiArrayKeyExist($data, 'policy', 'mobile_number'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Email Address<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="email_address" type="text" class="form-control email" value="<?php echo multiArrayKeyExist($data, 'policy', 'email_address'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Date of Transaction<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="date_of_transaction" type="text" class="form-control calendar-option" value="<?php echo multiArrayKeyExist($data, 'policy', 'date_of_transaction'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Reference Number<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="reference_number" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'reference_number'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Load Amount<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="load_amount" type="text" class="form-control money" value="<?php echo multiArrayKeyExist($data, 'policy', 'load_amount'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Load Status<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="load_status" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'load_status'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Consent Status<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="consent_status" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'consent_status'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Policy Id<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="policy_id" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'policy_id'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Policy Status<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="policy_status" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'policy_status'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Protect Premium Taxes<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="protect_premium_taxes" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'protect_premium_taxes'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Date Insurance Start<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="date_insurance_start" type="text" class="form-control calendar-option" value="<?php echo multiArrayKeyExist($data, 'policy', 'date_insurance_start'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Date Insurance End<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="date_insurance_end" type="text" class="form-control calendar-option" value="<?php echo multiArrayKeyExist($data, 'policy', 'date_insurance_end'); ?>" required>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Batch Number<span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="batch_number" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'policy', 'batch_number'); ?>" required>
            </div>
        </div>



        <div class="row mg-t-40 mg-b-10">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tx-center">
                <input name="id" type="hidden" class="form-control" value="<?php echo idEncrypt(multiArrayKeyExist($data, 'policy', 'id')); ?>">
                <button class="btn btn-primary w-150px submit"><small>SUBMIT</small></button>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $(document).on('click', '.submit', function(e){
            e.preventDefault();
            
            $('.required').remove();

            $('#form-policy input, #form-policy select, #form-policy textarea').each(
                function(index){  
                    var input   = $(this);
                    var prop    = input.prop("required");
                    var name    = input.prop("name");
                    var type    = input.prop("type");
                    var value   = input.val();
                    var parent  = input.parent();

                    if (typeof prop !== typeof undefined && prop !== false) {
                        if(value == '' || value == 0){
                            parent.append('<i class="required">required field</i>');
                        }
                    }
                }
            );

            if($('#form-policy .required').length <= 0) {
                $.ajax({
                    url: '/gcash/policy-json/',
                    type: 'POST',
                    data: $('#form-policy').serialize(),
                    beforeSend: function(){
                        promptAjaxLoading('modal-manage');
                    },
                    success: function(data){
                        promptAjaxSuccess('modal-manage', data.message, 'reload');
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
               });
            }
        });
    </script>