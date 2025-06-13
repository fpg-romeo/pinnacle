<div class="br-mainpanel mg-0">
    <div class="br-pagebody pd-0 mg-0">
        <form id="form-import-lead" method="post" enctype="multipart/form-data">
            <h6 class="mg-b-0 tx-uppercase tx-primary tx-bold modal-title">
                IMPORT DATA
                <br>
                <small class="text-muted">( Reminder: Please make sure all required information is not empty )</small>
            </h6>
            <hr>
            <div class="row mg-t-40 mg-b-20">
                <label class="col-sm-4 form-control-label">Transaction Type <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <div class="row mg-t-15">
                        <div class="col-lg-6">
                            <label class="rdiobox">
                                <input name="transaction_type" type="radio" value="new" checked>
                                <span>New<br><small class="text-muted">( Batch upload )</small></span>
                            </label>
                        </div>
                        <div class="col-lg-6 mg-t-20 mg-lg-t-0">
                            <label class="rdiobox">
                                <input name="transaction_type" type="radio" value="update">
                                <span>Update<br><small class="text-muted">( Maximum 500 rows of records only )</small></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mg-b-20">
                <label class="col-sm-4 form-control-label">Upload File <span class="tx-danger">*</span></label>
                <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                    <div class="custom-file">
                        <input name="file" id="file" type="file" class="custom-file-input">
                        <label class="custom-file-label"></label>
                    </div>
                </div>
            </div>
            <div class="row mg-t-40 mg-b-30">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tx-center">
                    <input name="id" type="hidden" class="form-control" value="<?php echo idEncrypt(multiArrayKeyExist($data, 'declaration', 'id')); ?>">
                    <button type="button" class="btn btn-primary w-150px upload"><small>SUBMIT</small></button>
                </div>
            </div>
        </form>
        <hr>
        <a href="/public/guide/Gcash-Claim-Template.xlsx" class="tx-bold tx-orange float-right" target="_blank" title="Download Gcash Claim Template" download="Import-Gcash-Claim-Template.xlsx"><i class="fa fa-file-excel-o"></i> DOWNLOAD TEMPLATE</a>
        <p class="msg mg-t-30 tx-danger hidden"></p>
        <div class="list hidden" style="overflow-x: auto;">
            <table class="table table-bordered bd mg-t-30">
                <thead class="bg-gray-100">
                    <th>
                        <center><input name="check_all_import" id="check_all_import" type="checkbox" checked></center>
                    </th>
                    <th>FIRST NAME</th>
                    <th>LAST NAME</th>
                    <th>MIDDLE NAME</th>
                    <th>BIRTHDAY</th>
                    <th>MOBILE</th>
                    <th>EMAIL</th>
                    <th>TRANSACTION DATE</th>
                    <th>REFERENCE NO</th>
                    <th>LOAD AMOUNT</th>
                    <th>LOAD STATUS</th>
                    <th>CONSENT STATUS</th>
                    <th>POLICY ID</th>
                    <th>POLICY STATUS</th>
                    <th>PREMIUM TAXES</th>
                    <th>INSURANCE START</th>
                    <th>INSURANCE END</th>
                    <th>BATCH NUMBER</th>
                    <th>SIMILAR POLICY NO</th>
                    <th>STATUS</th>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="row submit-upload-field hidden">
            <div class="col-md-12">
                <form id="form-import" method="post">
                    <center>
                        <input name="total_rows" type="hidden" class="form-control">
                        <input name="row" type="hidden" class="form-control">
                        <input name="duplicate" type="hidden" class="form-control">
                        <input name="file_temporary" type="hidden" class="form-control">
                        <input name="file_name" type="hidden" class="form-control">
                        <input name="redirect" type="hidden" class="form-control" value="<?php echo getVar('redirect'); ?>">
                        <button name="submit-import" type="submit" class="btn btn-primary btn-form"><small>SAVE</small></button>
                        <button class="btn btn-secondary btn-form modal-cancel" data-dismiss="modal"><small>CANCEL</small></button>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    //UPLOAD
    $(document).on('click', '.upload', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        var tbody = $('#modal-import tbody');
        var fd = new FormData();
        fd.append('file', document.getElementById('file').files[0]);

        $.ajax({
            url: '/gcash/import-claim-json/',
            type: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#modal-import .list').hide();
                $('#modal-import tbody').html('');
                $('#modal-import .modal-footer').hide();
                $('#modal-import .msg').hide();
                tbody.html('');
                promptAjaxLoading('modal-import');
            },
            success: function(data) {
                if (typeof data.alert !== typeof undefined && data.alert !== false) {
                    alert(data.alert);
                }
                promptAjaxSuccess('modal-import', data.message, data.redirect);
            },
            error: function(xhr, desc, err) {
                //console.log(xhr);
                console.warn(xhr.responseText);
            }
        });
    });
</script>

<script type="text/javascript">
    //IMPORT
    $(document).ready(function() {

        $(document).on('click', '#modal-import #check_all_import', function(e) {
            if ($(this).prop('checked')) {
                $('#modal-import input:checkbox').prop('checked', true);
            } else {
                $('#modal-import input[name=row]').val('');
                $('#modal-import input:checkbox').prop('checked', false);
            }
            importCheckbox();
        });
    });

    $(document).on('click', '#modal-import input[name=checkbox]', function(e) {
        if (!$(this).is(":checked")) {
            $('input[name="check_all_import"]').removeAttr("checked");
        }
        importCheckbox();
    });


    function importCheckbox() {
        var checkbox = [];
        $("#modal-import input[type=checkbox]:checked").each(function() {
            var id = $(this).val();
            if (id != '' && id != 'on') {
                checkbox.push(id);
            }
        });
        if (checkbox.length > 0) {
            $('#form-import .btn-form').attr('disabled', false)
        } else {
            $('#form-import .btn-form').attr('disabled', true)
        }
        console.log(checkbox);
        $('#modal-import input[name=row]').val(checkbox);
    }
</script>

<script type="text/javascript">
    //SUBMIT IMPORT
    $(document).ready(function() {
        $(document).on('click', 'button[name="submit-import"]', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            var checked_rows = $('#modal-import input[name=row]').val();

            if (checked_rows != '') {
                $.ajax({
                    url: '/gcash/import-claim-json/',
                    type: 'POST',
                    data: $('#form-import').serialize() + '&submit-import=',
                    beforeSend: function() {
                        promptAjaxLoading('modal-import');
                    },
                    success: function(data) {

                        if (typeof data.alert !== typeof undefined && data.alert !== false) {
                            alert(data.alert);
                        }
                        promptAjaxSuccess('modal-import', data.message, data.redirect);
                    },
                    complete: function() {

                    },
                    error: function(xhr, desc, err) {
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
                });
            } else {
                alert('Please select records to save')
            }
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $('input[name=confirm]').prop('checked', false);
        $('input[name=confirm]').click(function() {
            if ($(this).is(":checked")) {
                $(this).addClass("selected");
                $('button[name=submit]').removeAttr("disabled");
            } else {
                $(this).removeClass("selected");
                $('button[name=submit]').attr('disabled', true);
            }
        });
    });
</script>