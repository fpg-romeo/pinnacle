    <div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">Human Resources</a>
                <a class="breadcrumb-item" href="/">User</a>
                <span class="breadcrumb-item active">Records</span>
            </nav>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-user-circle"></i>
            <div>
                <h4><?php echo ACCOUNT_DEPARTMENT_NAME; ?> Department <b class="tx-primary">[ Records ]</b></h4>
                <p class="mg-b-0">Basic User Profile</p>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php flash(promptMessage('message')); ?>
        </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">
                <div class="row mg-b-20">
                    <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
                        <select name="pagination_limit" class="form-control select pagination" data-parameter="limit" data-placeholder="Limit" autocomplete="off">
                            <?php echo tool_dropdown_value(value_pagination_limit(), (getVar('limit') ? getVar('limit') : 10)); ?>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <input name="pagination_keyword" type="text" class="form-control pd-x-10 pagination" data-parameter="keyword" placeholder="Search..." value="<?php echo getVar('keyword'); ?>">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 d-flex align-items-center">
                        <label class="ckbox mg-t-5 d-flex align-items-center">
                            <input name="pagination_round_robin" type="checkbox" class="pd-x-10 pagination" data-parameter="round_robin" value="Yes" <?php echo (getVar('round_robin') === 'Yes') ? 'checked' : ''; ?>>
                            <span class="mg-l-5">Round Robin List</span>
                        </label>
                    </div>
                    <!-- <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2 d-flex align-items-center">
                        <label class="ckbox mg-t-5 d-flex align-items-center">
                            <input name="pagination_auto_allocate" type="checkbox" class="pd-x-10 pagination" data-parameter="auto_allocate" value="Yes" <?php echo (getVar('auto_allocate') === 'Yes') ? 'checked' : ''; ?>>
                            <span class="mg-l-5">Auto Allocate List</span>
                        </label>
                    </div> -->


                </div>             
                <div class="table-wrapper">
                    <!--<table class="table table-bordered display ">-->
                    <table class="table table-striped table-bordered bd mg-0 table-responsive d-md-table">
                        <thead class="thead-colored thead-dark">
                            <tr>
                                <th class="wd-5p tx-center">PHOTO</th>
                                <th class="wd-20p">NAME</th>
                                <th class="wd-20p">EMAIL / CONTACT NO.</th>
                                <th class="wd-15p">DESIGNATION</th>
                                <th class="wd-20p">DEPARTMENT</th>
                                <th class="wd-10p">TYPE</th>
                                <th class="wd-5p tx-center">STATUS</th>
                                <th class="wd-5p tx-center"><center>ACTION</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(is_array($data['user'])){
                                    foreach($data['user'] as $key => $value){
                                        echo '
                                            <tr id="'.$value['id'].'">
                                                <td class="tx-center cursor-pointer view-image" data-photo="'.htmlDecode($value['photo']).'" data-name="'.htmlDecode($value['first_name']).' '.htmlDecode($value['last_name']).'"><img src="'.displayImage(thumbnailName(htmlDecode($value['photo'])), 'account').'" class="img-fluid"></td>
                                                <td>
                                                    '.htmlDecode($value['first_name']).' '.htmlDecode($value['last_name']).'
                                                    '.(!empty(htmlDecode($value['alias'])) ? '<br><i class="text-muted">'.htmlDecode($value['alias']).'</i>' : '').'
                                                </td>
                                                <td>
                                                    '.htmlDecode($value['email']).'
                                                    <br><small class="text-muted">'.htmlDecode($value['contact_no']).'</small>
                                                </td>
                                                <td>
                                                    '.htmlDecode($value['account_designation_name']).'
                                                    '.(!empty($value['account_level_name']) ? '<br><small class="text-muted">'.htmlDecode($value['account_level_name']).'</small>' : '').'
                                                </td>
                                                <td>
                                                    '.htmlDecode($value['account_department_name']).'
                                                    '.(!empty($value['account_team_name']) ? '<br><small class="text-muted">'.htmlDecode($value['account_team_name']).'</small>' : '').'
                                                </td>
                                                <td>'.htmlDecode($value['account_type_name']).'</td>
                                                <td class="tx-center">'.htmlDecode($value['account_status_name']).'</td>
                                                <td class="tx-center">
                                                    <div class="dropdown d-inline-block">
                                                        <a href="" class="tx-gray-800 d-inline-block" data-toggle="dropdown">
                                                            <div class="pd-x-5 bd d-flex align-items-center justify-content-center">
                                                                <span><i class="fa fa-cog"></i></span>
                                                                <i class="fa fa-angle-down mg-l-10"></i>
                                                            </div>
                                                        </a>
                                                        <div class="dropdown-menu pd-5">
                                                            <nav class="nav nav-style-2 flex-column">
                                                                <a id="'.htmlDecode($value['id']).'" class="nav-link edit" data-action="edit" title="Update Record"><i class="fa fa-pencil-square-o"></i> Update</a>
                                                            </nav>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        ';
                                    }
                                }else{
                                    echo '<tr><td colspan="8" class="tx-center">No record found</td></tr>';
                                }
                            ?> 
                            
                        </tbody>
                    </table>
                </div>
                <?php if(is_array($data['user'])){ ?>
                    <div class="row mg-t-40">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 lh-22">
                            <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <ul class="pagination mg-0 float-right">
                                <?php echo tool_pagination(getVar('page'), $data['total_page'], '/account/user/', 'page', true); ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div id="modal-user" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center modal-lg" role="document">
            <form id="form-user">
                <div class="modal-content bd-0">
                    <div class="modal-header pd-y-20 pd-x-25">
                        <h6 class="tx-14 mg-b-0 tx-uppercase tx-primary tx-bold modal-title"><span></span> Record</h6>
                        <button type="button" class="close cursor-pointer" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body pd-25">
                        <div class="row mg-b-15">
                            <label class="col-sm-5 form-control-label">First Name <span class="tx-danger">*</span></label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <input name="first_name" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mg-b-15">
                            <label class="col-sm-5 form-control-label">Last Name <span class="tx-danger">*</span></label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <input name="last_name" type="text" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mg-b-15">
                            <label class="col-sm-5 form-control-label">Middle Name (Optional)</label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <input name="middle_name" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="row mg-b-15">
                            <label class="col-sm-5 form-control-label">Nickname</label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <input name="alias" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="row mg-b-15">
                            <label class="col-sm-5 form-control-label">Contact No.</label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <input name="contact_no" type="text" class="form-control">
                                <small class="text-muted">Please follow this format: <b>+65 0000 0000</b></small>
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-5 form-control-label">Department<span class="tx-danger">*</span></label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <select name="account_department_id" class="form-control select" data-width="100%" required>
                                    <?php echo tool_dropdown_option($data['account_department'], '', 'name'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-5 form-control-label">Team</label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <select name="account_team_id" class="form-control select" data-width="100%">
                                    <?php echo tool_dropdown_option($data['account_team'], '', 'name'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mg-b-20 account_designation">
                            <label class="col-sm-5 form-control-label">Designation<span class="tx-danger"></span></label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <select name="account_designation_id" class="form-control select" data-width="100%" disabled>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-5 form-control-label">Level<span class="tx-danger"></span></label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <select name="account_level_id" class="form-control select" data-width="100%">
                                    <?php echo tool_dropdown_option($data['account_level'], '', 'name'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-5 form-control-label">Report To<span class="tx-danger"></span></label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <select name="report_to" class="form-control select" data-width="100%">
                                    <?php echo tool_dropdown_option($data['account_all'], '', 'full_name'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-5 form-control-label">Employment Type</label>
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <select name="employment_type_id" class="form-control select" data-width="100%">
                                    <?php echo tool_dropdown_option($data['employment_type'], '', 'name'); ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-5 form-control-label">Baseline Sales Target</label>                          
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <input name="monthly_sales_target" type="text" class="form-control money" maxlength="7" autocomplete="off">
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-5 form-control-label">Minimum Baseline Sales Target</label>                          
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <input name="minimum_sales_target" type="number" class="form-control money" maxlength="7" autocomplete="off">
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-5 form-control-label">Maximum Baseline Sales Target</label>                          
                            <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                <input name="maximum_sales_target" type="number" class="form-control money" maxlength="7" autocomplete="off">
                            </div>
                        </div>
                        <div class="row mg-b-15">
                            <label class="col-sm-5 form-control-label">Client Appointment</label> 
                            <div class="col-sm-7">
                                <label class="ckbox mg-t-10">
                                    <input name="client_appointment" type="checkbox">
                                    <span>Include in Round Robin Scheduling</span>
                                </label>
                            </div>
                        </div>
                        <div class="row mg-b-15 hidden">
                            <label class="col-sm-5 form-control-label">Auto Allocate Lead</label> 
                            <div class="col-sm-7">
                                <label class="ckbox mg-t-10">
                                    <input name="auto_allocate_leads" type="checkbox">
                                    <span>Include in Auto Allocate Lead</span>
                                </label>
                            </div>
                        </div>
                        <hr>
                        <div class="row mg-t-20">
                            <div class="col-lg-12">
                                <label class="ckbox ">
                                    <input name="confirm" type="checkbox">
                                    <span>By checking, you are confirming all information is correct and updated</span>
                                </label>
                            </div>
                            <div class="col-lg-12 mg-t-40">
                                <center>
                                    <input name="account_id" type="hidden" class="form-control">
                                    <button name="submit" type="button" class="btn btn-info btn-form" disabled><small>SUBMIT</small></button>
                                    <button type="button" class="btn btn-secondary btn-form modal-cancel-reload" data-modal="modal"><small>CANCEL</small></button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="/public/lib/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/public/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
    <script src="/public/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/public/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
    <script type="text/javascript">
        //DATATABLE FILTER
        $(document).ready(function(){
            $('.pagination').bind('blur change',function(e){
                e.preventDefault();

                var link         = "/<?php echo getVar('controller').'/'.getVar('view'); ?>/";
                var limit        = $('select[name=pagination_limit]').find(":selected").val();
                var keyword      = encodeURIComponent($('input[name=pagination_keyword]').val());
                var round_robin  = $('input[name=pagination_round_robin]').is(':checked') ? 'Yes' : '';
                var auto_allocate  = $('input[name=pagination_auto_allocate]').is(':checked') ? 'Yes' : '';
                
                var parameter    = '?page=1&limit=' + limit + '&keyword=' + keyword + '&round_robin=' + round_robin + '&auto_allocate=' + auto_allocate;
                
                window.location.replace(link+parameter);
            });
        });
    </script>

    <script type="text/javascript">
        //CONFIRM
        $(document).ready(function(){
            $("input[name='confirm']").prop("checked", false);
            $("input[name='confirm']").click(function () {
                if($(this).is(":checked")){
                    $(this).addClass("selected");
                    $('#form-user button[name="submit"]').removeAttr("disabled");
                }else{
                    $(this).removeClass("selected");
                    $('#form-user button[name="submit"]').attr('disabled', true);
                }
            });
        });
    </script>

    <script type="text/javascript">
        //DELETE
        $(document).on('click', '.delete', function(e){
            e.preventDefault();
            var id      = $(this).attr('id');
            var action  = $(this).data('action');
            var title   = $(this).data('title');

            var check = confirm("Are you sure you want to delete?\n\n"+title);
            if(check == true){
                $.ajax({
                    url: '/account/delete-json/',
                    type: 'POST',
                    data: {id:id, action:action},
                    success: function(data){
                        console.log(data);
                        alert(data.message);
                        //location.reload();
                        window.location = document.URL;
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
               });
            }
        });

        //VIEW

        $(document).ready(function(){
            $(document).on('click', '.view', function(e){
                e.preventDefault();
                var id = $(this).data('id');
                var display = $('#modal_view .modal-body');
                // console.log(pipeline_id);
                $.ajax({
                    url: '/account/import-view/',
                    type: 'GET',
                    data: {
                        account_id:id
                    },
                    beforeSend: function(){
                        display.html('');
                    },
                    success: function(data){
                        // console.log('success');
                        $(data).appendTo(display);

                        $('#modal_view').modal('show');
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        //DEPARTMENT
        $(document).on('change', 'select[name=account_department_id]', function(e){
            var value               = $(this).find(":selected").val();

            // console.log(departments);
            dropdownDesignation(value);
            dropdownAssignedTo(value);
        });

        function dropdownAssignedTo(value, assigned_to=''){
            var assigned_to_id = $('select[name=assigned_to]');
            if(value == 0){
                assigned_to_id.html('');
                assigned_to_id.attr('disabled', true);
            }else{
                $.ajax({
                    url: '/account/per-department-manager-json/',
                    type: 'POST',
                    data: {id:value},
                    beforeSend: function(){
                        assigned_to_id.html('');
                        assigned_to_id.removeAttr('disabled');
                    },
                    success: function(data){
                        if(typeof data != "undefined" && data != null && data.length > 0){
                            var option = '';
                            $.each(data, function(index, item) {
                                if(item['id'] == assigned_to){
                                    selected = ' selected ';
                                }else{
                                    selected = '';
                                }
                                option += '<option value="'+item['account_id']+'" '+selected+'>'+item['full_name']+'</option>';
                            });
                            assigned_to_id.attr('required', true);
                            $('.assigned_to .tx-danger').html('*');
                        }else{
                            assigned_to_id.html('');
                            assigned_to_id.attr('disabled', true);
                            assigned_to_id.removeAttr('required');
                            $('.assigned_to .tx-danger').html('');
                        }
                
                        $(option).appendTo(assigned_to_id);

                        // console.log(data);
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
                });
            }
        }

        function dropdownDesignation(value, designation=''){
            var account_designation_id = $('select[name=account_designation_id]');
            if(value == 0){
                account_designation_id.html('');
                account_designation_id.attr('disabled', true);
            }else{
                $.ajax({
                    url: '/master/per-department-json/',
                    type: 'POST',
                    data: {id:value},
                    beforeSend: function(){
                        account_designation_id.html('');
                        account_designation_id.removeAttr('disabled');
                    },
                    success: function(data){
                        if(typeof data != "undefined" && data != null && data.length > 0){
                            var option = '';
                            $.each(data, function(index, item) {
                                if(item['id'] == designation){
                                    selected = ' selected ';
                                }else{
                                    selected = '';
                                }
                                option += '<option value="'+item['id']+'" '+selected+'>'+item['name']+'</option>';
                            });
                            account_designation_id.attr('required', true);
                            $('.account_designation .tx-danger').html('*');
                        }else{
                            account_designation_id.html('');
                            account_designation_id.attr('disabled', true);
                            account_designation_id.removeAttr('required');
                            $('.account_designation .tx-danger').html('');
                        }
                
                        $(option).appendTo(account_designation_id);

                        // console.log(data);
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
                });
            }
        }

        $(document).ready(function() {
            $('select[name=account_department_id]').trigger("change");
        });  
    </script>

    <script type="text/javascript">
        //EDIT
        $(document).ready(function(){
            $(document).on('click', '.edit', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                var account_id = $(this).attr('id');
                var action     = $(this).data('action');

                $('#modal-user').modal('show');

                $.ajax({
                    url: '/account/user-json/',
                    type: 'POST',
                    data: {account_id:account_id, action:action},
                    success: function(data){
                        $('input[name=account_id]').val(data.account_id);
                        $('input[name=first_name]').val(data.first_name);
                        $('input[name=last_name]').val(data.last_name);
                        $('input[name=middle_name]').val(data.middle_name);
                        $('input[name=alias]').val(data.alias);
                        $('input[name=contact_no]').val(data.contact_no);

                        $('input[name=monthly_sales_target]').val(formatMoney(data.monthly_sales_target));
                        $('input[name=minimum_sales_target]').val(formatMoney(data.minimum_sales_target));
                        $('input[name=maximum_sales_target]').val(formatMoney(data.maximum_sales_target));

                        $('select[name=account_team_id]').val(data.account_team_id).find("option[value=" + data.account_team_id +"]").attr('selected', true);
                        $('select[name=account_level_id]').val(data.account_level_id).find("option[value=" + data.account_level_id +"]").attr('selected', true);
                        $('select[name=report_to]').val(data.report_to).find("option[value=" + data.report_to +"]").attr('selected', true);
                        $('select[name=employment_type_id]').val(data.employment_type_id).find("option[value=" + data.employment_type_id +"]").attr('selected', true);

                        if(data.account_department_id != '' || data.account_department_id != '0'){
                            $('select[name=account_department_id]').val(data.account_department_id).find("option[value=" + data.account_department_id +"]").attr('selected', true);
                            dropdownDesignation(data.account_department_id,data.account_designation_id);
                        }

                        if(data.client_appointment == 'Yes'){
                            $('input[name=client_appointment]').prop('checked', true);
                        }else{
                            $('input[name=client_appointment]').prop('checked', false);
                        }

                        if(data.auto_allocate_leads == 'Yes'){
                            $('input[name=auto_allocate_leads]').prop('checked', true);
                        }else{
                            $('input[name=auto_allocate_leads]').prop('checked', false);
                        }

                        $('#modal-user .modal-title span').html('Edit');
                        $('#modal-user').modal('show');
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
                });
            });
        });
    </script>

    <script type="text/javascript">
        //SUBMIT
        $(document).on('click', 'button[name=submit]', function(e){
            e.preventDefault();
            
            $('.required').remove();

            $('#form-user input, #form-user select').each(
                function(index){  
                    var input   = $(this);
                    var prop    = input.prop("required");
                    var name    = input.prop("name");
                    var type    = input.prop("type");
                    var value   = input.val();
                    var parent  = input.parent();

                    if (typeof prop !== typeof undefined && prop !== false) {
                        if(value == ''){
                            parent.append('<i class="required">required field</i>');
                        }
                    }
                }
            );

            if($('#form-user .required').length <= 0) {
                $.ajax({
                    url: '/account/user-json/',
                    type: 'POST',
                    data: $('#form-user').serialize(),
                    beforeSend: function(){
                        promptAjaxLoading('form-user');
                    },
                    success: function(data){
                        promptAjaxSuccess('modal', data.message, 'reload');
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
               });
            }
        });
    </script>