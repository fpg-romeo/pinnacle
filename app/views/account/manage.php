<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">Account <span class="text-primary">[ Register ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a href="/account/all/1" class="btn btn-primary text-white">
                    <i class="icon-base ti tabler-arrow-left me-2"></i>
                    <span class="align-middle">Back</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-6">
                    <div class="card-body">
                        <form id="form-1" method="POST">
                            <div class="row g-6">
                                <!-- First / Middle / Last Name in 1 row -->
                                <div class="col-md-4">
                                    <label class="form-label" for="multicol-first-name">First Name</label>
                                    <input name="first_name" type="text" id="multicol-first-name" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="multicol-middle-name">Middle Name</label>
                                    <input name="middle_name" type="text" id="multicol-middle-name" class="form-control" />
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="multicol-last-name">Last Name</label>
                                    <input name="last_name" type="text" id="multicol-last-name" class="form-control" />
                                </div>

                                <!-- Continue with 2-column layout -->
                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-username">Username</label>
                                    <input name="active_directory" type="text" id="multicol-username" class="form-control" placeholder="P65XXXXX" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-email">Email</label>
                                    <input name="email" type="text" id="multicol-email" class="form-control" aria-describedby="multicol-email2" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-birthdate">Role</label>
                                    <select name="account_role_id" class="form-control select pagination" data-placeholder="Status">
                                        <?php echo tool_dropdown_option($data['account_role'], null, 'name'); ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-status">Status</label>
                                    <select name="account_status_id" class="form-control select pagination" data-placeholder="Status">
                                        <?php echo tool_dropdown_option($data['account_status'], null, 'name'); ?>
                                    </select>
                                </div>

                                <div class="pt-12 text-center">
                                    <button type="submit" class="btn btn-primary me-4">Submit</button>
                                    <button type="reset" class="btn btn-label-secondary">Cancel</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>