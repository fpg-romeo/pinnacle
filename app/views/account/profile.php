    <div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">User</a>
                <a class="breadcrumb-item" href="/">Profile</a>
                <span class="breadcrumb-item active">Records</span>
            </nav>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-user-circle"></i>
            <div>
                <h4>My Profile</h4>
                <p class="mg-b-0"></p>
            </div>
        </div> 
        <div class="br-pagebody">
            <?php flash(promptMessage('message')); ?>
        </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">
                <form id="form" role="form" method="post" enctype="multipart/form-data"> 
                    <div class="form-layout-4">
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Employee Number</label>
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                <?php echo multiArrayKeyExist($data, 'account', 'employee_no'); ?>
                            </div>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Full Name</label>
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                <?php echo multiArrayKeyExist($data, 'account', 'full_name'); ?>
                                <?php echo (!empty(multiArrayKeyExist($data, 'account', 'alias')) ? '<small class="text-muted">[ '.multiArrayKeyExist($data, 'account', 'alias').' ]</small>' : ''); ?>
                            </div>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Email Address</label>             
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                <?php echo multiArrayKeyExist($data, 'account', 'email'); ?>
                            </div>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Department</label>             
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                <?php echo (!empty(multiArrayKeyExist($data, 'account', 'account_department_name')) ? multiArrayKeyExist($data, 'account', 'account_department_name') : '-'); ?>
                            </div>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Team</label>             
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                <?php echo (!empty(multiArrayKeyExist($data, 'account', 'account_team_name')) ? multiArrayKeyExist($data, 'account', 'account_team_name') : '-'); ?>
                            </div>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Level</label>             
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                <?php echo (!empty(multiArrayKeyExist($data, 'account', 'account_level_name')) ? multiArrayKeyExist($data, 'account', 'account_level_name') : '-'); ?>
                            </div>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Report To</label>             
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                <?php echo (!empty(multiArrayKeyExist($data, 'account', 'report_to_name')) ? multiArrayKeyExist($data, 'account', 'report_to_name') : '-'); ?>
                            </div>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Designation</label>             
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                <?php echo (!empty(multiArrayKeyExist($data, 'account', 'account_designation_name')) ? multiArrayKeyExist($data, 'account', 'account_designation_name') : '-'); ?>
                            </div>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Client Appointment</label>             
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                <?php echo (!empty(multiArrayKeyExist($data, 'account', 'client_appointment')) ? multiArrayKeyExist($data, 'account', 'client_appointment') : 'No'); ?> <small>(Include in Round Robin Scheduling)</small>
                            </div>
                        </div>
                        <?php if(!empty(multiArrayKeyExist($data, 'account', 'monthly_sales_target')) && (multiArrayKeyExist($data, 'account', 'monthly_sales_target') != 0.00 && multiArrayKeyExist($data, 'account', 'monthly_sales_target') != 0)){ ?>
                        <div class="row mg-t-10">
                            <label class="col-sm-3">Monthly Sales Target</label>             
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0 tx-bold">
                                $<?php echo formatMoney(multiArrayKeyExist($data, 'account', 'monthly_sales_target')); ?>
                            </div>
                        </div>
                        <?php } ?>
                        <hr class="mg-b-30">
                        <div class="row mg-b-20">
                            <label class="col-sm-3 form-control-label">Nickname</label>             
                            <div class="col-sm-2 mg-t-10 mg-sm-t-0">
                                <input name="alias" type="text" class="form-control" maxlength="20" value="<?php echo multiArrayKeyExist( $data,'account','alias' ); ?>">
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-3 mg-t-12">Photo</label>
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                <input name="file_hidden" type="hidden" class="form-control" value="<?php echo multiArrayKeyExist($data, 'account', 'photo'); ?>">
                                <div class="custom-file">
                                    <input name="file" id="file" type="file" class="custom-file-input">
                                    <label class="custom-file-label"></label>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                    <?php
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
                                    ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <small class="text-muted">(Please fill in all the below fields if you want to update your password manually)</small>
                        <div class="row mg-y-20">
                            <label class="col-sm-3 form-control-label">Current Password: <span class="tx-danger">*</span></label>                          
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                <input name="password" type="password" class="form-control">
                                <?php echo multiArrayKeyExist($data, 'error', 'password'); ?> 
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-3 form-control-label">New Password: <span class="tx-danger">*</span></label>                          
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                <input name="new_password" type="password" class="form-control">
                                <?php echo multiArrayKeyExist($data, 'error', 'new_password'); ?> 
                            </div>
                        </div>
                        <div class="row mg-b-20">
                            <label class="col-sm-3 form-control-label">Confirm New Password: <span class="tx-danger">*</span></label>                          
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                <input name="confirm_password" type="password" class="form-control">
                                <?php echo multiArrayKeyExist($data, 'error', 'confirm_password'); ?> 
                            </div>
                        </div>
                        <div class="row mg-t-50">
                            <div class="col-sm-12 tx-center">
                                <button name="submit" type="submit" class="btn btn-primary btn-form"><small>SAVE</small></button>
                            </div>
                        </div>                         
                    </div>
                </form>
            </div>
        </div>
    </div>