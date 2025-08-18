<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">SOA <span class="text-primary">[ Letter Manage ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a href="/finance/soa-letter/1" class="btn btn-primary text-white">
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
                            <h6>1. Account Details</h6>
                            <div class="row g-6">
                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-username">Username</label>
                                    <input type="text" id="multicol-username" class="form-control" placeholder="john.doe" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-email">Email</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="multicol-email" class="form-control" placeholder="john.doe" aria-label="john.doe"  aria-describedby="multicol-email2" />
                                        <span class="input-group-text" id="multicol-email2">@example.com</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-password-toggle">
                                        <label class="form-label" for="multicol-password">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="multicol-password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="multicol-password2" />
                                            <span class="input-group-text cursor-pointer" id="multicol-password2">
                                                <i class="icon-base ti tabler-eye-off"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-password-toggle">
                                        <label class="form-label" for="multicol-confirm-password">Confirm Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="multicol-confirm-password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="multicol-confirm-password2" />
                                            <span class="input-group-text cursor-pointer" id="multicol-confirm-password2">
                                                <i class="icon-base ti tabler-eye-off"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr class="my-6 mx-n6" />
                            <h6>2. Personal Info</h6>
                            <div class="row g-6">
                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-first-name">First Name</label>
                                    <input type="text" id="multicol-first-name" class="form-control" placeholder="John" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-last-name">Last Name</label>
                                    <input type="text" id="multicol-last-name" class="form-control" placeholder="Doe" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-country">Country</label>
                                    <select id="multicol-country" class="select2 form-select" data-allow-clear="true">
                                        <option value="">Select</option>
                                        <option value="Australia">Australia</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                    </select>
                                </div>
                                <div class="col-md-6 select2-primary">
                                    <label class="form-label" for="multicol-phone">Phone No</label>
                                    <input type="text" id="multicol-phone" class="form-control phone-mask" placeholder="658 799 8941" aria-label="658 799 8941" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-birthdate">Birth Date</label>
                                    <input type="text" id="multicol-birthdate" class="form-control dob-picker" placeholder="YYYY-MM-DD" />
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="multicol-phone">Landline</label>
                                    <input type="text" id="multicol-phone" class="form-control phone-mask" placeholder="658 799 8941" aria-label="658 799 8941" />
                                </div>
                            </div>

                            <div class="pt-12 text-center">
                                <button type="submit" class="btn btn-primary me-4">Submit</button>
                                <button type="reset" class="btn btn-label-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>