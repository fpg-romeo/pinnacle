<div class="br-mainpanel mg-0">
    <div id="accordion" class="accordion accordion-head-colored accordion-info" role="tablist" aria-multiselectable="true">

        <div class="card">
            <div class="card-header">
                <h6 class="mg-b-0">
                    <a data-toggle="collapse" data-parent="#accordion" href="#tab-employment" aria-expanded="true" class="tx-gray-800 transition mg-b-0-force"><i class="fa fa-caret-right"></i> EMPLOYMENT INFORMATION</a>
                </h6>
            </div>
            <div id="tab-employment" class="collapse show">
                <div class="card-block pd-10">
                    <div class="bd rounded">
                        <table class="table table-bordered mg-b-0 table-responsive d-md-table">
                            <tr>
                                <td class="wd-20p">Active Directory</td>
                                <td class="wd-80p tx-bold"><?php echo multiArrayKeyExist($data, 'account', 'active_directory') ?></td>
                            </tr>
                            <tr>
                                <td>Email Address</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_employment', 'email') ?></td>
                            </tr>
                            <tr>
                                <td>Region/Branch</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_employment', 'account_region_name') ?></td>
                            </tr>
                            <tr>
                                <td>Department</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_employment', 'account_department_name') ?></td>
                            </tr>
                            <tr>
                                <td>Team</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_employment', 'account_team_name') ?></td>
                            </tr>
                            <tr>
                                <td>Designation</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_employment', 'account_designation_name') ?></td>
                            </tr>
                            <tr>
                                <td>Level</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_employment', 'account_level_name') ?></td>
                            </tr>
                            <tr>
                                <td>Report To</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_employment', 'report_name') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" role="tab" id="headingFive">
                <h6 class="mg-b-0">
                    <a data-toggle="collapse" data-parent="#accordion" href="#tab-personal" aria-expanded="false" class="collapsed tx-gray-800 transition mg-b-0-force"><i class="fa fa-caret-right"></i> PERSONAL PARTICULARS</a>
                </h6>
            </div>
            <div id="tab-personal" class="collapse">
                <div class="card-block pd-10">
                    <div class="bd rounded">
                        <table class="table table-bordered mg-b-0 table-responsive d-md-table">
                            <tr>
                                <td class="wd-20p">First Name</td>
                                <td class="wd-80p tx-bold"><?php echo multiArrayKeyExist($data, 'account_personal', 'first_name') ?></td>
                            </tr>
                            <tr>
                                <td>Last Name</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_personal', 'last_name') ?></td>
                            </tr>
                            <tr>
                                <td>Middle Name</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_personal', 'middle_name') ?></td>
                            </tr>
                            <tr>
                                <td>Nick Name</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_personal', 'alias') ?></td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_personal', 'gender') ?></td>
                            </tr>
                            <tr>
                                <td>Date of Birth</td>
                                <td class="tx-bold"><?php echo dateDisplaySystem(multiArrayKeyExist($data, 'account_personal', 'birthday')) ?></td>
                            </tr>
                            <tr>
                                <td>Age</td>
                                <td class="tx-bold"><?php echo birthday(multiArrayKeyExist($data, 'account_personal', 'birthday')) ?></td>
                            </tr>
                            <tr>
                                <td>Contact Number</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account_personal', 'contact_no') ?></td>
                            </tr>
                            <tr>
                                <td>Photo</td>
                                <td><div class="account-photo"><img src="<?php echo multiArrayKeyExist($data, 'account_personal', 'photo') ? $data['account_personal']['photo'] : '/public/img/no-photo.jpg'; ?>" class="img-fluid"></div></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header" role="tab" id="headingFive">
                <h6 class="mg-b-0">
                    <a data-toggle="collapse" data-parent="#accordion" href="#tab-system" aria-expanded="false" class="collapsed tx-gray-800 transition mg-b-0-force"><i class="fa fa-caret-right"></i> SYSTEM ACCOUNT</a>
                </h6>
            </div>
            <div id="tab-system" class="collapse">
                <div class="card-block pd-10">
                    <div class="bd rounded">
                        <table class="table table-bordered mg-b-0 table-responsive d-md-table">
                            <tr>
                                <td class="wd-20p">Status</td>
                                <td class="wd-80p tx-bold"><?php echo multiArrayKeyExist($data, 'account', 'account_status_name') ?></td>
                            </tr>
                            <tr>
                                <td>Account Id</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account', 'account_id') ?></td>
                            </tr>
                            <tr>
                                <td>Email Address</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account', 'email') ?></td>
                            </tr>
                            <tr>
                                <td>Account Type</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account', 'account_type_name') ?></td>
                            </tr>
                            <tr>
                                <td>Role</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'account', 'account_role_name') ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>