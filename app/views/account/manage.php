    <div class="br-mainpanel">
        <div class="br-pageheader justify-content-between">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">User</a>
                <a class="breadcrumb-item" href="/">Profile</a>
                <span class="breadcrumb-item active">Records</span>
            </nav>
            <div class="w-300px mg-r-10">
                <div class="input-group">
                <input type="text" name="keyword_user" type="text" class="form-control"  placeholder="Search..." onkeyup="onSearch()">
                    <div class="input-group-append">
                        <button name="search_user" class="btn btn-info" type="button" disabled>
                        <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-user-circle"></i>
            <div>
                <h4>User Profile <b class="tx-primary"><?php managePageTitle('id'); ?></b></h4>
                <p class="mg-b-0">System Access</p>
                <div class="pagetitle-button">
                    <a href="/account/all/1/" class="btn btn-info">
                        <i class="fa fa-list-ul fa-lg"></i> <small>LIST</small>
                    </a>
                </div>
            </div>
        </div> 
        <div class="br-pagebody">
            <?php flash(promptMessage('message')); ?>
        </div>

        <div class="br-pagebody">
            <form id="account-form" role="form" method="post" enctype="multipart/form-data"> 

                <div class="card card-collapsable shadow-base widget-11 mg-b-10">
                    <div class="card-header card-sub-menu pd-20">
                        <div class="card-title">
                            <span class="tx-13">System Account</span>
                        </div>
                        <i class="fa fa-plus card-icon tx-primary"></i>
                    </div><!-- card-header -->
                    <div class="card-body show">
                        <div class="form-layout-4 bd-0 pd-t-0 pd-b-0">
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Required to relogin</label>                         
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <label class="ckbox mg-t-8">
                                        <input name="relogin" type="checkbox" <?php echo multiArrayKeyExist($data, 'account', 'relogin') == 'Yes' ? 'checked' : ''; ?>>
                                        <span>Force user to logout</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Email Address<span class="tx-danger">*</span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="employee_email" type="text" class="form-control employee_email" value="<?php echo multiArrayKeyExist($data, 'account_employment', 'email'); ?>" required>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Password<?php echo !isset($data['account']['id']) ? '<span class="tx-danger">*</span>' : ''; ?></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="password" type="password" class="form-control" <?php echo !isset($data['account']['id']) ? 'required' : ''; ?>>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Type<span class="tx-danger">*</span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_type_id" class="form-control select" data-width="100%" required>
                                        <?php echo tool_dropdown_option($data['account_type'], multiArrayKeyExist($data, 'account_employment', 'account_type_id'), 'name'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Role<span class="tx-danger">*</span></label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0 checkbox_role">

                                </div>
                            </div>  
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Status<span class="tx-danger">*</span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_status_id" class="form-control select" data-width="100%" required>
                                        <?php echo tool_dropdown_option($data['account_status'], multiArrayKeyExist($data, 'account_employment', 'account_status_id'), 'name'); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-block -->
                </div>

                <div class="card card-collapsable shadow-base widget-11 mg-b-10">
                    <div class="card-header card-sub-menu pd-20">
                        <div class="card-title">
                            <span class="tx-13">Employment Information</span>
                        </div>
                        <i class="fa fa-plus card-icon tx-primary"></i>
                    </div><!-- card-header -->
                    <div class="card-body hidden">
                        <div class="form-layout-4 bd-0 pd-t-0 pd-b-0">
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Employee No.<span class="tx-danger">*</span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="employee_no" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_employment', 'employee_no'); ?>" >
                                    <small class="text-muted"></small>
                                </div>
                                <label class="col-sm-5 form-control-label text-muted tx-11">*Please set the value as 0 "zero" if employee number is not yet available</label>  
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Email Address</label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="employee_email" type="text" class="form-control employee_email" value="<?php echo multiArrayKeyExist($data, 'account_employment', 'email'); ?>" required>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Department<span class="tx-danger">*</span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_department_id" class="form-control select" data-width="100%" required>
                                        <?php echo tool_dropdown_option($data['account_department'], multiArrayKeyExist($data, 'account_employment', 'account_department_id'), 'name'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Team</label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_team_id" class="form-control select" data-width="100%">
                                        <?php echo tool_dropdown_option($data['account_team'], multiArrayKeyExist($data, 'account_employment', 'account_team_id'), 'name'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-20 account_designation">
                                <label class="col-sm-3 form-control-label">Designation<span class="tx-danger"></span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_designation_id" class="form-control select" data-width="100%">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Level<span class="tx-danger"></span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_level_id" class="form-control select" data-width="100%">
                                        <?php echo tool_dropdown_option($data['account_level'], multiArrayKeyExist($data, 'account', 'account_level_id'), 'name'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Report To<span class="tx-danger"></span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="report_to" class="form-control select" data-width="100%">
                                        <?php echo tool_dropdown_option($data['account_all'], multiArrayKeyExist($data, 'account', 'report_to'), 'full_name'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-15">
                                <label class="col-sm-3 form-control-label lh-0-force">Client Appointment</label> 
                                <div class="col-sm-9">
                                    <label class="ckbox mg-t-5">
                                        <input name="client_appointment" <?php echo !empty(multiArrayKeyExist($data, 'account_employment', 'client_appointment')) && multiArrayKeyExist($data, 'account_employment', 'client_appointment') == 'Yes' ? 'checked' : ''; ?> type="checkbox">
                                        <span>Include in Round Robin Scheduling</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row mg-b-15 hidden">
                                <label class="col-sm-3 form-control-label">Auto Allocate Lead</label> 
                                <div class="col-sm-9">
                                    <label class="ckbox mg-t-10">
                                        <input name="auto_allocate_leads" <?php echo !empty(multiArrayKeyExist($data, 'account_employment', 'auto_allocate_leads')) && multiArrayKeyExist($data, 'account_employment', 'auto_allocate_leads') == 'Yes' ? 'checked' : ''; ?> type="checkbox">
                                        <span>Include in Auto Allocate Lead</span>
                                    </label>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Joined Date<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="start_date" type="text" class="form-control calendar" placeholder="MM/DD/YYYY" autocomplete="off" value="<?php echo isset($data['account_employment']['start_date']) && $data['account_employment']['start_date']!='1970-01-01' ? dateDisplaySystem($data['account_employment']['start_date']) : ''; ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Confirmation Date<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="confirmation_date" type="text" class="form-control calendar" placeholder="MM/DD/YYYY" autocomplete="off" value="<?php echo isset($data['account_employment']['confirmation_date']) && $data['account_employment']['confirmation_date']!='1970-01-01' ? dateDisplaySystem($data['account_employment']['confirmation_date']) : ''; ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Exit Date<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="exit_date" type="text" class="form-control calendar" placeholder="MM/DD/YYYY" autocomplete="off" value="<?php echo isset($data['account_employment']['exit_date']) && $data['account_employment']['exit_date']!='1970-01-01' ? dateDisplaySystem($data['account_employment']['exit_date']) : ''; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card card-collapsable shadow-base widget-11 mg-b-10">
                    <div class="card-header card-sub-menu pd-20">
                        <div class="card-title">
                            <span class="tx-13">Personal Particulars</span>
                        </div>
                        <i class="fa fa-plus card-icon tx-primary"></i>
                    </div><!-- card-header -->
                    <div class="card-body hidden">
                        <div class="form-layout-4 bd-0 pd-t-0 pd-b-0">
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Full name<span class="tx-danger">*</span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="first_name" type="text" class="form-control" placeholder="First Name" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'first_name'); ?>" required>
                                </div>
                                <div class="col-sm-3 mg-t-10 mg-sm-t-0">
                                    <input name="last_name" type="text" class="form-control" placeholder="Last Name" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'last_name'); ?>" required>
                                </div>
                                <div class="col-sm-2 mg-t-10 mg-sm-t-0">
                                    <input name="middle_name" type="text" class="form-control" placeholder="Middle Name (Optional)" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'middle_name'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Nickname<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="alias" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'alias'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Gender<span class="tx-danger">*</span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="gender" class="form-control select" data-width="100%" required>
                                        <?php echo tool_dropdown_value(value_gender(), multiArrayKeyExist($data, 'account_personal', 'gender')); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Date of Birth<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="birthday" type="text" class="form-control calendar" placeholder="MM/DD/YYYY" autocomplete="off" value="<?php echo isset($data['account_personal']['birthday']) && $data['account_personal']['birthday']!='1970-01-01' ? dateDisplaySystem($data['account_personal']['birthday']) : ''; ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Religion<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="religion" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'religion'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Highest Educational Attainment<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="education" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'education'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Marital Status<span class="tx-danger">*</span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="marital_status" class="form-control select" data-width="100%" required>
                                        <?php echo tool_dropdown_value(value_marital_status(), multiArrayKeyExist($data, 'account_personal', 'marital_status')); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Number of Children<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="no_children" type="text" class="form-control numeric" maxlength="2" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'no_children'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Contact Number<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="contact_no" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'contact_no'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Landline Number<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="landline_no" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'landline_no'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Personal Email Address<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="personal_email" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'email'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Current Address<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <textarea name="address_current" class="form-control no-resize"><?php echo multiArrayKeyExist($data, 'account_personal', 'address_current'); ?></textarea>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Hometown Address<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <textarea name="address_hometown" class="form-control no-resize"><?php echo multiArrayKeyExist($data, 'account_personal', 'address_hometown'); ?></textarea>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Medical History (if any)<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <textarea name="medical_history" class="form-control no-resize"><?php echo multiArrayKeyExist($data, 'account_personal', 'medical_history'); ?></textarea>
                                </div>
                            </div>

                            <div class="row mg-t-20">
                                <label class="col-sm-3 mg-t-12">Photo</label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="file_hidden" type="hidden" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'photo'); ?>">
                                    <div class="custom-file">
                                        <input name="file" id="file" type="file" class="custom-file-input">
                                        <label class="custom-file-label"></label>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <!-- C jae - New Photo -->
                                            <?php
                                                if(!empty(multiArrayKeyExist($data, 'account_personal', 'photo'))){
                                                    echo '
                                                            <div class="row">
                                                                <div class="col-sm-12 mg-y-5">
                                                                    <div class="account-photo"><img src="'.multiArrayKeyExist($data, 'account_personal', 'photo').'" class="img-fluid"></div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <label class="ckbox">
                                                                        <input name="file_delete" type="checkbox">
                                                                        <span>DELETE</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        ';
                                                }
                                            ?>
                                            <!-- C jae - Old Photo -->
                                            <!-- <?php
                                                    if(!empty(multiArrayKeyExist($data, 'account', 'photo'))){
                                                        echo '
                                                                <div class="row">
                                                                    <div class="col-sm-12 mg-y-5">
                                                                        <div class="account-photo"><img src="'.displayImage(thumbnailName(multiArrayKeyExist($data, 'account', 'photo')), 'account').'" class="img-fluid"></div>
                                                                    </div>
                                                                    <div class="col-sm-12">
                                                                        <label class="ckbox">
                                                                            <input name="file_delete" type="checkbox">
                                                                            <span>DELETE</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            ';
                                                    }
                                            ?> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- card-block -->
                </div>
                <div class="br-section-wrapper pd-15">
                    <div class="row mg-b-20">
                        <div class="col-lg-12">
                            <label class="ckbox">
                                <input name="confirm_checkbox" type="checkbox">
                                <span>By checking, you are confirming all information is correct and updated</span>
                                
                            </label>
                        </div>
                    </div>
                    <div class="row  tx-center">
                        <div class="col-sm-12 mg-t-10 mg-b-10 mg-sm-t-0">
                            <button name="submit" type="submit" class="btn btn-primary w-200px confirm_button" disabled><small>SUBMIT</small></button>
                        </div>
                    </div>  
                </div>

            </form>
        </div>
    </div>

    <script type="text/javascript">
        //CONFIRM
        $(document).ready(function(){
            $("input[name='confirm_checkbox']").prop("checked", false);
            $("input[name='confirm_checkbox']").click(function () {
                if($(this).is(":checked")){
                    $(this).addClass("selected");
                    $('.confirm_button').removeAttr("disabled");
                }else{
                    $(this).removeClass("selected");
                    $('.confirm_button').attr('disabled', true);
                }
            });
        });
    </script>

    <script type="text/javascript">
        function onSearch(){
            var searchBox = encodeURIComponent($('input[name="keyword_user"]').val());
            if(searchBox == ""){
                $('button[name=search_user]').attr('disabled',true);
            }else{
                $('button[name=search_user]').attr('disabled',false);
            }
        }

        $(document).ready(function(){
            onSearch();
            $(document).on('click', 'button[name=search_user]', function(e){
                var searchBoxVal = encodeURIComponent($('input[name="keyword_user"]').val());
                var redirect_url = '/account/all/?page=1&limit=10&keyword='+searchBoxVal;
                window.location.href = redirect_url;
            })
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            var role      = '<?php echo $data['role_list']; ?>';
            var user_role = '<?php echo multiArrayKeyExist($data, 'account_employment', 'account_role_id'); ?>';

            checkboxRole(role,user_role);
        });

        function checkboxRole(list, row=''){
            var arr = list.split('+');

            $('.checkbox_role').html('');
            $.each(arr, function(index, value){

                var check   = '';
                var data    = value.split('_');
                var item    = row.split('-');
                var popover = '';

                if($.inArray(data[0], item) != '-1'){
                    check = 'checked';
                }else{
                    check = '';
                }

                if(typeof data[2] != "undefined" && data[2] != null && data[2].length > 0 && data[2] != ''){
                    popover = '<button type="button" class="btn btn-default" data-container="body" data-toggle="popover" data-popover-color="default" data-placement="top" title="" data-content="'+data[2]+'">'+
                                '<i class="fa fa-question-circle-o" aria-hidden="true"></i>'+
                              '</button>';
                }

                $('.checkbox_role').append('<label class="ckbox mg-b-10 ckbox-inline">'+
                                                '<input name="account_role_id[]" type="checkbox" value="'+data[0]+'" '+check+'>'+
                                                '<span>'+data[1]+' </span>'+
                                                popover+
                                            '</label>'
                                          );
            });

            $('[data-toggle="popover"]').popover();
        }
    </script>    

    <script type="text/javascript">
        //DEPARTMENT
        $(document).on('change', 'select[name=account_department_id]', function(e){
            var value               = $(this).find(":selected").val();
            var designation         = "<?php echo multiArrayKeyExist($data, 'account_employment', 'account_designation_id'); ?>";
            var departments         = <?php echo json_encode($data['account_department'],JSON_INVALID_UTF8_SUBSTITUTE); ?> ;
            var role                = '<?php echo $data['role_list']; ?>';
            var user_role           = '<?php echo multiArrayKeyExist($data, 'account_employment', 'account_role_id'); ?>';
            var user_department     = '<?php echo multiArrayKeyExist($data, 'account_employment', 'account_department_id'); ?>';
            
            // console.log(departments);
            dropdownDesignation(value, designation);
        });

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
        
        $(document).on('click','.card-sub-menu',function(){
            var body = $(this).next();
            var icon = $(this).find('.card-icon');

            if ( body.hasClass('show') ) {
                body.slideUp().removeClass('show');
                icon.toggleClass("spin").removeClass('fa-minus');
                icon.addClass('fa-plus');
            }else{
                body.slideDown().addClass('show');
                icon.toggleClass("spin").removeClass('fa-plus');
                icon.addClass('fa-minus');
            }
        });

    </script>

    <script type="text/javascript">
        $(document).on('click','button[name="submit"]',function(e){
            $('#account-form input[required]').each(function(){
                var input = $(this).val();
                if ( input == '' ) {
                    $(this).parents('.card').find('.card-body').removeClass('show');
                    $(this).parents('.card').find('.card-sub-menu').click();
                }
            });

            $('#account-form select[required]').each(function(){
                var select = $(this).val();
                if ( select == '' ) {
                    $(this).parents('.card').find('.card-body').removeClass('show');
                    $(this).parents('.card').find('.card-sub-menu').click();
                }
            });
        });
    </script>

    <script type="text/javascript">
        //EMAIL
        $(document).on('keypress change','.employee_email',function(){
            var email = $(this).val();
            $('input[name=employee_email]').val(email);
        });
    </script>