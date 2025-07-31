    <div class="br-mainpanel mg-0">
        <div class="br-pagebody pd-0 mg-0">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h6 class="br-section-label tx-info mg-t-0 float-left">DECLARATION DETAILS</h6>
                    <h6 class="br-section-label tx-info mg-t-0 float-right">RECORD ID: <?php echo multiArrayKeyExist($data, 'claim', 'batch_id'); ?></h6>
                    <table class="table table-bordered bd mg-b-0">
                        <tbody>
                            <tr>
                                <td class="wd-30p">Batch Number</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'batch_number'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Workflow Number</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'workflow_number'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Endorsement Number</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'endorsement_number'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Full Name</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'first_name').' '.multiArrayKeyExist($data, 'claim', 'middle_name').' '.multiArrayKeyExist($data, 'claim', 'last_name'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Date of Birth</td>
                                <td class="wd-70p tx-bold"><?php echo dateDisplaySystem($data['claim']['date_of_birth']); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Mobile Number</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'mobile_number'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Email Address</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'email_address'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Date of By Load Transaction</td>
                                <td class="wd-70p tx-bold"><?php echo dateDisplaySystem($data['claim']['date_of_transaction']); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Buy Load Reference Number</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'reference_number'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Buy Load Amount</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'load_amount'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Buy Load Status</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'load_status'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">GInsure Consent Status</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'consent_status'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Policy ID</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'policy_id'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">GInsure Policy Status</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'policy_status'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Buy Load Protect Premium w/ Taxes</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'claim', 'protect_premium_taxes'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Date of Insurance Start</td>
                                <td class="wd-70p tx-bold"><?php echo dateDisplaySystem($data['claim']['date_insurance_start']); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Date of Insurance End</td>
                                <td class="wd-70p tx-bold"><?php echo dateDisplaySystem($data['claim']['date_insurance_end']); ?></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>