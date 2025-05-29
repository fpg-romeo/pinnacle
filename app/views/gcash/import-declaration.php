    <div class="br-mainpanel mg-0">
        <div class="br-pagebody pd-0 mg-0">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <h6 class="br-section-label tx-info mg-t-0 float-left">DECLARATION DETAILS</h6>
                    <form id="form-submit-declaration" method="post">
                        <table class="table table-bordered bd mg-b-0">
                            <tbody>
                                <tr>
                                    <td class="wd-30p">Batch Number</td>
                                    <td class="wd-70p tx-bold">
                                        <input type="text" class="form-control" name="batch_number" value="<?php echo multiArrayKeyExist($data, 'declaration', 'batch_number'); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="wd-30p">Workflow Number</td>
                                    <td class="wd-70p tx-bold">
                                        <input type="text" class="form-control" name="workflow_number" value="<?php echo multiArrayKeyExist($data, 'declaration', 'workflow_number'); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="wd-30p">Endorsement Number</td>
                                    <td class="wd-70p tx-bold">
                                        <input type="text" class="form-control" name="endorsement_number" value="<?php echo multiArrayKeyExist($data, 'declaration', 'endorsement_number'); ?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="hidden" name="declaration_id" value="<?php echo multiArrayKeyExist($data, 'declaration', 'id'); ?>">
                        <input type="hidden" name="action" value="<?php echo arrayKeyExist($data, 'action'); ?>">
                        <input type="submit" name="submit-declaration" class="btn btn-info float-right mg-t-10" value="SUBMIT">
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">

        var action = <?= json_encode($data['action']);?>;

        if(action == 'update' || action == 'add') {
            $('input[type="text"]').each(function() {
                $(this).prop('readonly', false);
            });

            $('[name="submit-declaration"]').removeClass('hidden');
        }
        else{
            $('input[type="text"]').each(function() {
                $(this).prop('readonly', true);
                $('[name="submit-declaration"]').addClass('hidden');
            });
        }

        $(document).on('click', '[name="submit-declaration"]', function(e) {
            e.stopImmediatePropagation();
            $.ajax({
                url: '/gcash/submit-declaration-json/',
                type: 'POST',
                data: $('#form-submit-declaration').serialize() + '&submit-declaration=',
                beforeSend: function() {
                    promptAjaxLoading('modal-import-declaration');
                },
                success: function(data) {
                    if (typeof data.alert !== typeof undefined && data.alert !== false) {
                        alert(data.alert);
                    }
                    promptAjaxSuccess('modal-import-declaration', data.message, data.redirect);
                },
                complete: function() {

                },
                error: function(xhr, desc, err) {
                    console.log(xhr);
                    console.warn(xhr.responseText);
                }
            });
        });
    </script>