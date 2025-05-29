    <div class="br-mainpanel mg-0">
        <div class="br-pagebody pd-0 mg-0">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h6 class="br-section-label tx-info mg-t-0 float-left">DETAILS</h6>
                    <h6 class="br-section-label tx-info mg-t-0 float-right">RECORD ID: <?php echo multiArrayKeyExist($data, 'declaration', 'id'); ?></h6>
                    <table class="table table-bordered bd mg-b-0">
                        <tbody>
                            <tr>
                                <td class="wd-30p">Batch Number</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'declaration', 'batch_number'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Workflow Number</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'declaration', 'workflow_number'); ?></td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Endorsement Number</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'declaration', 'endorsement_number'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>