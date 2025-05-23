<div class="br-mainpanel mg-0">
    <div class="br-pagebody pd-0 mg-0">
        <form id="form-import-lead" method="post" enctype="multipart/form-data">
            <h6 class="mg-b-0 tx-uppercase tx-primary tx-bold modal-title">IMPORT DATA</h6>
            <hr>
            <div class="row">
                <div class="col-sm-3">
                    <div class="custom-file">
                        <input name="file" id="file" type="file" class="custom-file-input">
                        <label class="custom-file-label"></label>
                    </div>
                </div>
                <div class="col-sm-3">
                    <button type="button" class="btn btn-info upload"><i class="fa fa-cloud-upload fa-lg"></i> <small>UPLOAD</small></button>
                </div>
            </div>
        </form>
        <small class="text-muted">(Reminder: Please make sure all required information is not empty)</small>
        <br>
        <br>
        <a href="/public/guide/Gcash-Claim-Template.xlsx" class="tx-bold tx-orange" target="_blank" title="Download Gcash Claim Template" download="Import-Gcash-Claim-Template.xlsx"><i class="fa fa-file-excel-o"></i> DOWNLOAD TEMPLATE</a>
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

                if (typeof data.row != "undefined" && data.row != null && data.row.length > 0) {
                    var list = '';
                    var row = [];
                    var checked = '';
                    var disabled = '';
                    var count_rows = 0;
                    $.each(data.row, function(index, item) {

                        if (item['status'] == 'New') {
                            checkbox = '<input name="checkbox" id="checkbox-' + parseInt(index + 1) + '" type="checkbox" value="' + item['row'] + '" checked>';
                            disabled = '';

                            $('#modal-import #check_all_import').prop('checked', true);
                            $('#modal-import .submit-upload-field').show();
                            row.push(item['row']);
                        } else {
                            if (item['is_similar_only'] == 'Yes') {
                                checkbox = '<input name="checkbox" id="checkbox-' + parseInt(index + 1) + '" type="checkbox" value="' + item['row'] + '" checked>';
                                disabled = 'bg-gray-100 tx-warning';
                                row.push(item['row']);
                            } else {

                                checkbox = '';
                                disabled = 'bg-gray-200 tx-danger';
                                $('#modal-import .submit-upload-field').show();
                            }
                        }
                        list += '<tr class="' + disabled + '">' +
                            '<td><center>' + checkbox + '</center></td>' +
                            '<td>' + (item['first_name'] == null ? '' : item['first_name']) + '</td>' +
                            '<td>' + (item['last_name'] == null ? '' : item['last_name']) + '</td>' +
                            '<td>' + (item['middle_name'] == null ? '' : item['middle_name']) + '</td>' +
                            '<td>' + (item['date_of_birth'] == null ? '' : item['date_of_birth']) + '</td>' +
                            '<td>' + (item['mobile_number'] == null ? '' : item['mobile_number']) + '</td>' +
                            '<td>' + (item['email_address'] == null ? '' : item['email_address']) + '</td>' +
                            '<td>' + (item['date_of_transaction'] == null ? '' : item['date_of_transaction']) + '</td>' +
                            '<td>' + (item['reference_number'] == null ? '' : item['reference_number']) + '</td>' +
                            '<td>' + (item['load_amount'] == null ? '' : item['load_amount']) + '</td>' +
                            '<td>' + (item['load_status'] == null ? '' : item['load_status']) + '</td>' +
                            '<td>' + (item['consent_status'] == null ? '' : item['consent_status']) + '</td>' +
                            '<td>' + (item['policy_id'] == null ? '' : item['policy_id']) + '</td>' +
                            '<td>' + (item['policy_status'] == null ? '' : item['policy_status']) + '</td>' +
                            '<td>' + (item['protect_premium_taxes'] == null ? '' : item['protect_premium_taxes']) + '</td>' +
                            '<td>' + (item['date_insurance_start'] == null ? '' : item['date_insurance_start']) + '</td>' +
                            '<td>' + (item['date_insurance_end'] == null ? '' : item['date_insurance_end']) + '</td>' +
                            '<td>' + (item['similar_name'] == null ? '' : item['similar_name'].replace(/[,]+/g, '<br>')) + '</td>' +
                            '<td>' + item['status'] + '</td>' +
                            '</tr>';
                        count_rows++;
                    });
                    $('#modal-import input[name=total_rows]').val(count_rows);
                    $('#modal-import input[name=row]').val(row);
                    $('#modal-import input[name=duplicate]').val(data.duplicate);
                    $(list).appendTo(tbody);
                    $('#modal-import .list').show();

                } else {
                    $('#modal-import .msg').show().html(data.message);
                    //$('#modal-import .msg').html(data.message);
                }

                $('input[name=file_temporary]').val(data.file_temporary);
                $('input[name=file_name]').val(data.file_name);
                modalLoadingRemove('modal-import');
                //console.log(data);
                importCheckbox();
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