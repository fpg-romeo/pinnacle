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
                            <div class="row mg-t-20" id="transfer-to-house-div" hidden>
                                <label class="col-sm-3 form-control-label"></label>
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <label class="ckbox mg-b-10"><input name="transfer_to_house" <?php echo !empty(multiArrayKeyExist($data, 'account', 'transfer_leads')) && multiArrayKeyExist($data, 'account', 'transfer_leads') == 'Yes' ? 'checked disabled' : ''; ?> type="checkbox" value="Yes"><span>Transfer all converted leads ownership to House Sales <b class="tx-danger">(Reminder this is not reversible)</b></span></label>
                                </div>
                            </div>
                            <div id="add-to-blacklist-div" class="hidden">
                                <div class="row mg-t-10">
                                    <label class="col-sm-3 form-control-label"></label>
                                    <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                        <label class="ckbox mg-b-10"><input name="add_user_to_blacklist" <?php echo is_array($data['account_blacklist']) ? 'checked disabled' : ''; ?> type="checkbox" value="Yes"><span>Add this user to blacklist</span></label>
                                        <textarea name="add_user_to_blacklist_remarks" class="form-control no-resize" <?php  echo is_array($data['account_blacklist']) ? 'disabled' : ''; ?> placeholder="Remarks/Reason"><?php echo multiArrayKeyExist($data, 'account_blacklist', 'remarks'); ?></textarea>
                                    </div>
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
                                <label class="col-sm-3 form-control-label">Region/Branch<span class="tx-danger">*</span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_region_id" class="form-control select" data-width="100%" required>
                                        <?php echo tool_dropdown_option($data['region'], multiArrayKeyExist($data, 'account_employment', 'account_region_id')); ?>
                                    </select>
                                </div>
                            </div>
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
                                    <select name="account_designation_id" class="form-control select" data-width="100%" disabled>
                                        
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
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Employment Type</label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="employment_type_id" class="form-control select" data-width="100%">
                                        <?php echo tool_dropdown_option($data['employment_type'], multiArrayKeyExist($data, 'account', 'employment_type_id'), 'name'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Baseline Sales Target</label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="monthly_sales_target" type="text" class="form-control money" maxlength="7" autocomplete="off" value="<?php echo formatMoney(multiArrayKeyExist($data, 'account_employment', 'monthly_sales_target')); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Minimum Baseline Sales Target</label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="minimum_sales_target" type="text" class="form-control money" maxlength="7" autocomplete="off" value="<?php echo formatMoney(multiArrayKeyExist($data, 'account_employment', 'minimum_sales_target')); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Maximum Baseline Sales Target</label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="maximum_sales_target" type="text" class="form-control money" maxlength="7" autocomplete="off" value="<?php echo formatMoney(multiArrayKeyExist($data, 'account_employment', 'maximum_sales_target')); ?>">
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
                                <label class="col-sm-3 form-control-label">Nationality<span class="tx-danger">*</span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="nationality_id" class="form-control select" data-width="100%" required>
                                        <?php echo tool_dropdown_option($data['nationality'], multiArrayKeyExist($data, 'account_personal', 'nationality_id')); ?>
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
                                <label class="col-sm-3 form-control-label">NRIC Number<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="nric_no" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_personal', 'nric_no'); ?>">
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

                <div class="card card-collapsable shadow-base widget-11 mg-b-10">
                    <div class="card-header card-sub-menu pd-20">
                        <div class="card-title">
                            <span class="tx-13">Bank Information</span>
                        </div>
                        <i class="fa fa-plus card-icon tx-primary"></i>
                    </div><!-- card-header -->
                    <div class="card-body hidden">
                        <div class="form-layout-4 bd-0 pd-t-0 pd-b-0">
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Bank Name<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="bank_name" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_bank', 'name'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Bank Account Number<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="bank_account_no" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_bank', 'account_no'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Payee Name (as of bank account)<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="bank_payee_name" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_bank', 'payee_name'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Bank Code<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="bank_code" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_bank', 'code'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Branch Code<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="bank_branch_code" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_bank', 'branch_code'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-collapsable shadow-base widget-11 mg-b-10">
                    <div class="card-header card-sub-menu pd-20">
                        <div class="card-title">
                            <span class="tx-13">Emergency Contact Person</span>
                        </div>
                        <i class="fa fa-plus card-icon tx-primary"></i>
                    </div><!-- card-header -->
                    <div class="card-body hidden">
                        <div class="form-layout-4 bd-0 pd-t-0 pd-b-0">
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Name<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="emergency_contact_name" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_emergency_contact', 'name'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Contact Number<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="emergency_contact_no" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_emergency_contact', 'contact_no'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Relationship<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <input name="emergency_contact_relationship" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_emergency_contact', 'relationship'); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-collapsable shadow-base widget-11 mg-b-10">
                    <div class="card-header card-sub-menu pd-20">
                        <div class="card-title">
                            <span class="tx-13">Equipment Assignment</span>
                        </div>
                        <i class="fa fa-plus card-icon tx-primary"></i>
                    </div><!-- card-header -->
                    <div class="card-body hidden">
                        <div class="form-layout-4 bd-0 pd-t-0 pd-b-0">
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Computer Type<span class="tx-danger"></span></label>                          
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="computer_type" class="form-control select" data-width="100%">
                                        <?php echo tool_dropdown_value(value_computer_type(), multiArrayKeyExist($data, 'account_equipment', 'computer_type')); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Computer Serial Number<span class="tx-danger"></span></label>                          
                                <div class="col-sm-6 mg-t-10 mg-sm-t-0">
                                    <input name="computer_serial_no" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_equipment', 'computer_serial_no'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Locker Number<span class="tx-danger"></span></label>                          
                                <div class="col-sm-2 mg-t-10 mg-sm-t-0">
                                    <input name="locker_no" type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account_equipment', 'locker_no'); ?>">
                                </div>
                            </div>
                            <div class="row mg-b-20">
                                <label class="col-sm-3 form-control-label">Accessories<span class="tx-danger"></span></label>                          
                                <div class="col-sm-9 mg-t-10 mg-sm-t-0">
                                    <textarea name="accessories" rows="5" class="form-control no-resize"><?php echo multiArrayKeyExist($data, 'account_equipment', 'accessories'); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card card-collapsable shadow-base widget-11 mg-b-10" id="account_promotion" hidden>
                    <div class="card-header card-sub-menu pd-20">
                        <div class="card-title">
                            <span class="tx-13">Promotion</span>
                        </div>
                        <i class="fa fa-plus card-icon tx-primary"></i>
                    </div><!-- card-header -->
                    <div class="card-body hidden">
                        <div class="table-wrapper ">
                            <table class="table table-bordered table-striped bd mg-b-0 mg-t-20">
                                <thead class="bg-gray-100 tx-bold tx-center">
                                    <tr>
                                        <th class="wd-20p tx-center">DEPARTMENT</th>
                                        <th class="wd-20p">DESIGNATION</th>
                                        <th class="wd-20p">TEAM</th>
                                        <th class="wd-20p">LEVEL</th>
                                        <th class="wd-20p">ACTIVE?</th>
                                        <th class="wd-15p"><center>ACTION</center></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                         if(is_array($data['account_promotion'])){
                                            foreach($data['account_promotion'] as $key => $value){
                                                $is_active = $value['promotion_status'] == 'Active' ? 'Yes' : 'No';
                                                echo '<tr>
                                                    <td  class="tx-center">'.$value['account_department_name'].'</td>
                                                    <td  class="tx-center">'.$value['account_designation_name'].'</td>
                                                    <td  class="tx-center">'.$value['account_team_name'].'</td>
                                                    <td  class="tx-center">'.$value['account_level_name'].'</td>
                                                    <td  class="tx-center">'.$is_active.'</td>
                                                    <td  class="tx-center"> 
                                                        <center>
                                                        
                                                        <a id="'.htmlDecode($value['id']).'" class="btn btn-sm btn-list btn-warning edit-promotion" data-action="edit" title="Update Record"><i class="fa fa-pencil-square-o"></i></a>
                                                        <a id="'.htmlDecode($value['id']).'" class="btn btn-sm btn-list btn-danger delete-promotion" data-action="delete" data-title="'.htmlDecode($value['account_department_name']).'" title="Delete Record"><i class="fa fa-trash"></i></a>   
                                                        </center>
                                                        </td>
                                                    </tr>';
                                            }
                                         }else{
                                            echo '<tr><td colspan="6" class="tx-center">No record found</td></tr>';
                                         }
                                    ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="form-layout-4 bd-0 pd-t-20 pd-b-0" id="account-promotion-div">
                            <div class="row justify-content-center mg-b-20">
                                <label class="col-sm-3 form-control-label">Department<span class="tx-danger">*</span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_promotion_department_id" class="form-control select" data-width="100%" >
                                        <?php echo tool_dropdown_option($data['account_department'], '', 'name'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-center mg-b-20 account_designation">
                                <label class="col-sm-3 form-control-label">Designation<span class="tx-danger"></span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_promotion_designation_id" class="form-control select" data-width="100%" disabled>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-center mg-b-20">
                                <label class="col-sm-3 form-control-label">Team</label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_promotion_team_id" class="form-control select"  data-width="100%">
                                        <?php echo tool_dropdown_option($data['account_team'], '', 'name'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-center mg-b-20">
                                <label class="col-sm-3 form-control-label">Level<span class="tx-danger"></span></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <select name="account_promotion_level_id" class="form-control select" data-width="100%">
                                        <?php echo tool_dropdown_option($data['account_level'], '', 'name'); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row justify-content-center mg-t-20" >
                                <label class="col-sm-3 form-control-label"></label>
                                <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                    <label class="ckbox mg-b-10"><input name="is_promotion_active"  type="checkbox" value="Yes"><span>Apply this information to current employee record?</span></label>
                                </div>
                            </div>
                            
                            <div class="row justify-content-center  tx-center">
                                <div class="col-sm-12 mg-t-10 mg-sm-t-0">
                                    <input type="text"  name="account_id"  hidden   id="account_id" value="<?php echo !empty(getVar('id')) ? idDecrypt(getVar('id')): ''; ?>">
                                    <input type="text"  name="promotion_id" hidden    id="promotion_id">
                                    <button name="submit-promotion" type="button" class="btn btn-primary w-200px submit-promotion" ><small>SUBMIT PROMOTION</small></button>
                                    <button name="cancel-promotion" type="button" class="btn btn-list btn-secondary w-150px " ><small>CANCEL</small></button>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>

                <div class="br-section-wrapper pd-15">
                    <div class="row mg-b-20">
                        <div class="col-lg-12">
                            <label class="ckbox">
                                <input name="confirm_checkbox" type="checkbox">
                                <span>By checking, you are confirming all information is correct and updated</span>
                                
                            </label>
                            <label class="hidden" id="transfer-to-house-warning">
                                <span  class="tx-danger mg-l-30"><b>You agree to transfer all converted lead ownership to House Sales</b></span>
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

 <!-- check mo to  -->
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
            var isUrlHasAccountId   = id != '' ? true : false;
            if(isUrlHasAccountId == false){
                if(value != ''){
                    var selected_department = departments.find(x => x.id == value);
                    if(selected_department != '' && selected_department.account_role_ids != ''){
                        checkboxRole(role,selected_department.account_role_ids)
                        console.log(selected_department.account_role_ids);
                    }
                }
            }else{
                if(value != ''){
                    if(user_department != value){
                        var selected_department = departments.find(x => x.id == value);
                        if(selected_department != '' && selected_department.account_role_ids != ''){
                            checkboxRole(role,selected_department.account_role_ids)
                        }

                    }else{
                        checkboxRole(role,user_role);
                    }
                }
            }
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

    <script type="text/javascript">
        var url = window.location.href.split( '/' );
        var id = url[5];
        if(id != ''){
            $("#account_promotion").attr("hidden",false);
            // $("select[name=account_promotion_department_id]").attr("required",true);
            var isAllreadyTrasnferred = $("input[name=transfer_to_house]").is(":checked");
            $(document).ready(function() {
                
                var account_status = $('select[name=account_status_id]').find(":selected").val();
                if(account_status != '' && account_status != 1 && account_status != 2){
                    $("#transfer-to-house-div").prop('hidden',false);
                    
                    if(account_status == 3 || account_status == 4){
                        $("#add-to-blacklist-div").removeClass('hidden');
                    }else{
                        $("#add-to-blacklist-div").addClass('hidden');
                    }
                }else{
                    $("#transfer-to-house-div").prop('hidden',true);
                    $("#add-to-blacklist-div").addClass('hidden');
                }
            });

            $(document).on('change', 'select[name=account_status_id]', function(e){
                var value = $(this).find(":selected").val();
                if(value != '' && value != 1 && value != 2){
                    $("#transfer-to-house-div").prop('hidden',false);

                    if(value == 3 || value == 4){
                        $("#add-to-blacklist-div").removeClass('hidden');
                    }else{
                        $("#add-to-blacklist-div").addClass('hidden');
                    }
                    
                    if(isAllreadyTrasnferred == true){
                        $("input[name=transfer_to_house]").prop('checked', true); 
                    }else{
                        $("input[name=transfer_to_house]").prop('checked', false); 
                    }
                }else{
                    $("#transfer-to-house-div").prop('hidden',true);
                    $("#add-to-blacklist-div").addClass('hidden');
                    if(isAllreadyTrasnferred == true){
                        $("input[name=transfer_to_house]").prop('checked', true); 
                    }else{
                        $("input[name=transfer_to_house]").prop('checked', false); 
                    }
                }
            });

            $(document).on('change', 'input[name=transfer_to_house]', function(e){
                if(this.checked) {
                    $("#transfer-to-house-warning").removeClass('hidden');

                }else{
                    $("#transfer-to-house-warning").addClass('hidden');
                }
                
            });
        }else{
            $("#account_promotion").attr("hidden",true);
            // $("select[name=account_promotion_department_id]").attr("required",false);
        }
    </script>

<script type="text/javascript">
        //account promotion
        $(document).on('change', 'select[name=account_promotion_department_id]', function(e){
            var value       = $(this).find(":selected").val();
            var designation = "";

            dropdownPromotionDesignation(value, designation);
        });

        function dropdownPromotionDesignation(value, designation=''){
            var account_promotion_designation_id = $('select[name=account_promotion_designation_id]');
            if(value == 0){
                account_promotion_designation_id.html('');
                account_promotion_designation_id.attr('disabled', true);
            }else{
                $.ajax({
                    url: '/master/per-department-json/',
                    type: 'POST',
                    data: {id:value},
                    beforeSend: function(){
                        account_promotion_designation_id.html('');
                        account_promotion_designation_id.removeAttr('disabled');
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
                            account_promotion_designation_id.attr('required', true);
                            $('.account_promotion_designation_id .tx-danger').html('*');
                        }else{
                            account_promotion_designation_id.html('');
                            account_promotion_designation_id.attr('disabled', true);
                            account_promotion_designation_id.removeAttr('required');
                            $('.account_promotion_designation_id .tx-danger').html('');
                        }
                
                        $(option).appendTo(account_promotion_designation_id);

                        console.log(data);
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
                });
            }
        }

        $(document).ready(function() {
            $('select[name=account_promotion_department_id]').trigger("change");
        });  
    </script>

<script type="text/javascript">
        //ADD PROMOTION
        $(document).on('click', '.submit-promotion', function(e){
            e.preventDefault();
            $('#account-promotion-div .required').remove();

            $('#account-promotion-div input, #account-promotion-div select').each(
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
            var id      = $("input[name=promotion_id]").val();
            // var action  = id != '' ? 'edit' : 'add';
            var account_id                          = $("input[name=account_id]").val();
            var account_promotion_department_id     = $( "select[name=account_promotion_department_id] option:selected" ).val();
            var account_promotion_designation_id    = $( "select[name=account_promotion_designation_id] option:selected" ).val();
            var account_promotion_team_id           = $( "select[name=account_promotion_team_id] option:selected" ).val();
            var account_promotion_level_id          = $( "select[name=account_promotion_level_id] option:selected" ).val();
            var is_promotion_active                 = $('input[name=is_promotion_active]').is(":checked")
            if($('#account-promotion-div .required').length <= 0) {
                $.ajax({
                        url: '/account/account-promotion-json/',
                        type: 'POST',
                        data: {
                            id: id,
                            // action: action,
                            account_id: account_id,
                            account_promotion_department_id: account_promotion_department_id,
                            account_promotion_designation_id: account_promotion_designation_id,
                            account_promotion_team_id: account_promotion_team_id,
                            account_promotion_level_id: account_promotion_level_id,
                            is_promotion_active:is_promotion_active
                        },  
                        success: function(data){
                            // console.log(data);
                            alert(data.message);
                            window.location = document.URL;
                        },
                        error: function(xhr, desc, err){ 
                            //console.log(xhr);
                            console.warn(xhr.responseText);
                        }
                });
            }
        });

        // //EDIT PROMOTION
        $(document).ready(function(){
            $(document).on('click', '.edit-promotion', function(e){
                e.preventDefault();
                var id      = $(this).attr('id');
                var action  = $(this).data('action');
                var editButtons = $('.edit-promotion');
                var deleteButtons = $('.delete-promotion');
                // $("input[name=promotion_id]").val(id);
                editButtons.addClass("disabled");
                deleteButtons.addClass("disabled");


                $.ajax({
                    url: '/account/account-promotion-json/',
                    type: 'POST',
                    data: {id:id, action:action},
                    success: function(data){
                        $("input[name=promotion_id]").val(data.id);
                        $('select[name=account_promotion_department_id]').val(data.account_department_id).find("option[value=" + data.account_department_id +"]").attr('selected', true);
                       
                        $('select[name=account_promotion_department_id]').trigger("change");
                        setTimeout(()=>{
                            $('select[name=account_promotion_designation_id]').val(data.account_designation_id).find("option[value=" + data.account_designation_id +"]").attr('selected', true);
                            $('select[name=account_promotion_team_id]').val(data.account_team_id).find("option[value=" + data.account_team_id +"]").attr('selected', true);
                            $('select[name=account_promotion_level_id]').val(data.account_level_id).find("option[value=" + data.account_level_id +"]").attr('selected', true);
                            $('select[name=account_promotion_designation_id]').trigger("change");
                            $('select[name=account_promotion_team_id]').trigger("change");
                            $('select[name=account_promotion_level_id]').trigger("change");
                            if(data.promotion_status == 'Active'){
                                $('input[name=is_promotion_active]').attr('checked', true); // Checks it
                            }else{
                                $('input[name=is_promotion_active]').attr('checked', false); 
                            }
                        },500)
                        
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
               });
            });
            $(document).on('click', 'button[name="cancel-promotion"]', function(e){
                e.preventDefault();
                var editButtons = $('.edit-promotion');
                var deleteButtons = $('.delete-promotion');
                $("input[name=promotion_id]").val("");
                
                $('select[name=account_promotion_department_id]').val("");
                $('select[name=account_promotion_designation_id]').val("");
                $('select[name=account_promotion_team_id]').val("");
                $('select[name=account_promotion_level_id]').val("");
                $('select[name=account_promotion_department_id]').trigger("change");
                $('select[name=account_promotion_designation_id]').trigger("change");
                $('select[name=account_promotion_team_id]').trigger("change");
                $('select[name=account_promotion_level_id]').trigger("change");
                editButtons.removeClass("disabled");
                deleteButtons.removeClass("disabled");
                $('input[name=is_promotion_active]').attr('checked', false); 
            });
        });

        //DELETE Promotion
        $(document).on('click', '.delete-promotion', function(e){
            e.preventDefault();
            var id      = $(this).attr('id');
            var action  = $(this).data('action');

            var check = confirm("Are you sure you want to delete this promotion?");
            if(check == true){
                $.ajax({
                    url: '/account/account-promotion-json/',
                    type: 'POST',
                    data: {id:id, action:action},
                    success: function(data){
                        alert(data.message);
                        window.location = document.URL;
                    },
                    error: function(xhr, desc, err){ 
                        console.warn(xhr.responseText);
                    }
               });
            }
        });
    </script>
