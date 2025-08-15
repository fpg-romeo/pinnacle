<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-align-top">
                    <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
                        <li class="nav-item">
                            <a class="nav-link active" href="/account/profile"><i class="icon-base ti tabler-user icon-sm me-1_5"></i> Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/account/security"><i class="icon-base ti tabler-lock icon-sm me-1_5"></i> Security</a>
                        </li>
                    </ul>
                </div>
                <div class="card mb-6">
                    <div class="card-body">
                        <div class="d-flex align-items-start align-items-sm-center gap-6">
                            <img src="/public/img/avatars/1.png" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                            <div class="button-wrapper">
                                <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
                                    <span class="d-none d-sm-block">Upload new photo</span>
                                    <i class="icon-base ti tabler-upload d-block d-sm-none"></i>
                                    <input type="file" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                </label>
                                <button type="button" class="btn btn-label-secondary account-image-reset mb-4">
                                    <i class="icon-base ti tabler-reset d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>
                                <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4">
                        <form id="form" role="form" method="post" enctype="multipart/form-data">
                            <div class="row gy-4 gx-6 mb-6">
                                <div class="col-md-6 form-control-validation">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input class="form-control" type="text" id="firstName" name="firstName" value="<?php echo multiArrayKeyExist($data, 'account', 'first_name'), ' ' .   multiArrayKeyExist($data, 'account', 'middle_name'); ?>" autofocus />
                                </div>
                                <div class="col-md-6 form-control-validation">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input class="form-control" type="text" name="lastName" id="lastName" value="<?php echo multiArrayKeyExist($data, 'account', 'last_name') ?>" />
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control" type="text" id="email" name="email" value="<?php echo multiArrayKeyExist($data, 'account', 'email') ?>" placeholder="email" />
                                </div>
                                <div class="col-md-6">
                                    <label for="organization" class="form-label">Organization</label>
                                 
                                        <select name="account_department_id" class="form-control select" style="width: 100%" data-placeholder="---" required>
                                            <?php echo tool_dropdown_option(multiKeyExists($data, 'department'), multiArrayKeyExist($data, 'account', 'account_department_id'), 'name'); ?>
                                        </select>
                               
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="phoneNumber">Phone Number</label>
                                    <div class="input-group input-group-merge">
                                        <span class="input-group-text">PH (+63)</span>
                                        <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" placeholder="911 111 2222" value="<?php echo multiArrayKeyExist($data, 'account', 'contact_no') ?>"/>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" />
                                </div>
                                <div class="col-md-6">
                                    <label for="state" class="form-label">State</label>
                                    <input class="form-control" type="text" id="state" name="state" placeholder="California" />
                                </div>
                                <div class="col-md-6">
                                    <label for="zipCode" class="form-label">Zip Code</label>
                                    <input type="text" class="form-control" id="zipCode" name="zipCode" placeholder="231465" maxlength="6" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="country">Country</label>
                                    <select id="country" class="select2 form-select">
                                        <option value="">Select</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <option value="Belarus">Belarus</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="language" class="form-label">Language</label>
                                    <select id="language" class="select2 form-select">
                                        <option value="">Select Language</option>
                                        <option value="en">English</option>
                                        <option value="fr">French</option>
                                        <option value="de">German</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="timeZones" class="form-label">Timezone</label>
                                    <select id="timeZones" class="select2 form-select">
                                        <option value="">Select Timezone</option>
                                        <option value="-12">(GMT-12:00) International Date Line West</option>
                                        <option value="-11">(GMT-11:00) Midway Island, Samoa</option>
                                        <option value="-10">(GMT-10:00) Hawaii</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="currency" class="form-label">Currency</label>
                                    <select id="currency" class="select2 form-select">
                                        <option value="">Select Currency</option>
                                        <option value="usd">USD</option>
                                        <option value="euro">Euro</option>
                                        <option value="pound">Pound</option>
                                    </select>
                                </div>
                            </div> -->
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-primary me-3">Save changes</button>
                                    <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>
                <!-- <div class="card">
                    <h5 class="card-header">Delete Account</h5>
                    <div class="card-body">
                        <div class="mb-6 col-12 mb-0">
                            <div class="alert alert-warning">
                                <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                                <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                            </div>
                        </div>
                        <form id="formAccountDeactivation" onsubmit="return false">
                            <div class="form-check my-8">
                                <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
                                <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
                            </div>
                            <button type="submit" class="btn btn-danger deactivate-account" disabled>Deactivate Account</button>
                        </form>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>




<!-- <div class="br-pagebody">
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
                                <?php echo (!empty(multiArrayKeyExist($data, 'account', 'alias')) ? '<small class="text-muted">[ ' . multiArrayKeyExist($data, 'account', 'alias') . ' ]</small>' : ''); ?>
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
                        <?php if (!empty(multiArrayKeyExist($data, 'account', 'monthly_sales_target')) && (multiArrayKeyExist($data, 'account', 'monthly_sales_target') != 0.00 && multiArrayKeyExist($data, 'account', 'monthly_sales_target') != 0)) { ?>
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
                                <input name="alias" type="text" class="form-control" maxlength="20" value="<?php echo multiArrayKeyExist($data, 'account', 'alias'); ?>">
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
                                    if (!empty(multiArrayKeyExist($data, 'account', 'photo'))) {
                                        echo '
                                                    <div class="row">
                                                        <div class="col-sm-12 mg-y-5">
                                                            <div class="account-photo"><img src="' . displayImage(thumbnailName(multiArrayKeyExist($data, 'account', 'photo')), 'account') . '" class="img-fluid"></div>
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
        </div> -->
</div>