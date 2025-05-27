    <div class="br-mainpanel mg-0">
        <div class="br-pagebody pd-0 mg-0">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h6 class="br-section-label tx-info mg-t-0 float-left">DECLARATION DETAILS</h6>
                    <table class="table table-bordered bd mg-b-0">
                        <tbody>
                            <tr>
                                <td class="wd-30p">Batch Number</td>
                                <td class="wd-70p tx-bold">
                                    <input type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'declaration', 'batch_number'); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Workflow Number</td>
                                <td class="wd-70p tx-bold">
                                    <input type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'declaration', 'workflow_number'); ?>">
                                </td>
                            </tr>
                            <tr>
                                <td class="wd-30p">Endorsement Number</td>
                                <td class="wd-70p tx-bold">
                                    <input type="text" class="form-control" value="<?php echo multiArrayKeyExist($data, 'declaration', 'endorsement_number'); ?>">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="declaration_id" value="<?php echo multiArrayKeyExist($data, 'declaration', 'id'); ?>">
                    <input type="submit" name="update-declaration" class="btn btn-info float-right mg-t-10" value="Update Declaration">
                </div>
            </div>

        </div>
    </div>