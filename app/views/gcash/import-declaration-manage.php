    <form id="form-declaration" method="post"> 
        <div class="row mg-b-20">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h6 class="br-section-label tx-info mg-t-0 float-left">MANAGE RECORD</h6>
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Batch Number <span class="tx-danger">*</span></label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="batch_number" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'declaration', 'batch_number'); ?>">
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Workflow Number</label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="workflow_number" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'declaration', 'workflow_number'); ?>">
            </div>
        </div>
        <div class="row mg-b-20">
            <label class="col-sm-4 form-control-label">Endorsement Number</label>
            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                <input name="endorsement_number" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'declaration', 'endorsement_number'); ?>">
            </div>
        </div>
        <div class="row mg-t-50">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tx-center">
                <input name="id" type="hidden" class="form-control" value="<?php echo idEncrypt(multiArrayKeyExist($data, 'declaration', 'id')); ?>">
                <button class="btn btn-primary w-150px submit"><small>SUBMIT</small></button>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        $(document).on('click', '.submit', function(e){
            e.preventDefault();
            
            $('.required').remove();

            $('#form-declaration input, #form-declaration select, #form-declaration textarea').each(
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

            if($('#form-declaration .required').length <= 0) {
                $.ajax({
                    url: '/gcash/declaration-json/',
                    type: 'POST',
                    data: $('#form-declaration').serialize(),
                    beforeSend: function(){
                        promptAjaxLoading('modal-declaration');
                    },
                    success: function(data){
                        promptAjaxSuccess('modal-declaration', data.message, 'reload');
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
               });
            }
        });
    </script>