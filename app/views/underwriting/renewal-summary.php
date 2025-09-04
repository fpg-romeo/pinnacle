<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">Underwriting <span class="text-primary">[ Renewal ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a class="btn btn-info text-white import" action="add" data-bs-toggle="modal" data-bs-target="#modal-import">
                    <i class="icon-base ti tabler-upload me-2"></i>
                    <span class="align-middle">Upload</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                                <select name="pagination_limit" class="form-select select2 pagination" data-parameter="limit" data-placeholder="Limit" autocomplete="off">
                                    <?php echo tool_dropdown_value(value_pagination_limit(), (getVar('limit') ? getVar('limit') : 10)); ?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <select name="pagination_account_id" class="form-control select2 pagination" data-placeholder="Filter By Account">
                                    <?php
                                    if (isset($data['accounts']) && count($data['accounts']) > 1) {
                                        echo '<option value="all" ' . (getVar('account_id') == 'all' ? 'selected' : "") . '>All</option>';
                                    }
                                    ?>
                                    <?php echo tool_dropdown_option($data['accounts'], (getVar('account_id') ? getVar('account_id') : ''), 'full_name'); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-2">
                        <div class="nav-align-top nav-tabs-shadow">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a
                                        type="button"
                                        class="nav-link active"
                                        href=""
                                        data-bs-toggle="tab"
                                        aria-selected="true">
                                        Summary
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        href="/underwriting/renewal/"
                                        type="button"
                                        class="nav-link"
                                        aria-controls="navs-top-align-profile"
                                        aria-selected="false">
                                        All Records
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="table-responsive no-wrap">
                                    <table class="table table-striped table-bordered table-hover mg-b-0">
                                        <thead class="thead-colored">
                                            <tr>
                                                <th class="wd-10p tx-center">MANAGED DATE</th>
                                                <th class="wd-10p tx-center">UPLOAD ID</th>
                                                <th class="wd-10p tx-center">NO. OF <br>UPLOAD</th>
                                                <th class="wd-10p tx-center">NO. OF <br>DUPLICATE</th>
                                                <th class="wd-10p tx-center">NO. OF <br>FAILED</th>
                                                <th class="wd-10p tx-center">NO. OF <br>DELETED</th>
                                                <th class="wd-10p tx-right">TOTAL</th>
                                                <th class="wd-10p tx-center">MANAGED BY</th>
                                                <th class="wd-20">UPLOADED FILE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($data['summary']) && !empty($data['summary'])) {
                                                    $total = 0;
                                                    foreach ($data['summary'] as $key_summary => $value_summary) {

                                                        $count = ($value_summary['success'] + $value_summary['duplicate'] + $value_summary['failed'] + $value_summary['deleted'] + $value_summary['manual_entry']);

                                                        echo '
                                                                <tr >
                                                                    <td class="tx-center">' . dateDisplaySystem($value_summary['created_when']) . '</td>
                                                                    <td class="tx-center">' . $value_summary['id'] . '</td>
                                                                    <td class="tx-center">' . formatNumber($value_summary['success']) . '</td>
                                                                    <td class="tx-center">' . formatNumber($value_summary['duplicate']) . '</td>
                                                                    <td class="tx-center">' . formatNumber($value_summary['failed']) . '</td>
                                                                    <td class="tx-center">' . formatNumber($value_summary['deleted']) . '</td>
                                                                    <td class="tx-right">' . formatNumber($count) . '</td>
                                                                    <td class="tx-center">' . htmlDecode($value_summary['account_name']) . '</td>
                                                                    <td>' . (!empty($value_summary['file']) ? '<span class="tx-14 valign-top"><i class="icon ion-android-attach"></i><small> <a href="/file/gcash/' . htmlDecode($value_summary['file']) . '" target="_blank">' . htmlDecode($value_summary['file_name']) . '</a></small></span>' : '') . '</td>
                                                                </tr>
                                                            ';

                                                        $total += $count;
                                                    }

                                                    echo '<tr class="tx-bold">
                                                            <td colspan="6" class="tx-right">Total records: </td>
                                                            <td class="tx-right">'.formatNumber($total).'</td>
                                                            <td colspan="2">&nbsp;</td>
                                                            </tr>';

                                                } else {
                                                    echo '<tr><td colspan="9" class="tx-center">No record found</td></tr>';
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php if(is_array($data['summary'])){ ?>
                            <div class="row justify-content-between">
                                <div class="col-md-auto me-auto mt-8">
                                    <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                </div>

                                <div class="col-md-auto ms-auto mt-5">
                                    <ul class="pagination">
                                        <?php echo tool_pagination(getVar('page'), $data['total_page'], '/underwriting/renewal-summary/', 'page', true); ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-import" class="modal fade">
            <div class="modal-dialog modal-dialog-vertical-center modal-lg">
                <div class="modal-content bd-0">
                    <div class="modal-body pd-25">

                    </div>
                </div>
            </div>
        </div>

        <div id="modal-view" class="modal fade">
            <div class="modal-dialog modal-dialog-vertical-center modal-xl">
                <div class="modal-content bd-0">
                    <div class="modal-body pd-25">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //DATATABLE FILTER
    $(document).ready(function() {
        $('.pagination').bind('blur change', function(e) {
            e.preventDefault();

            var link = "/<?php echo getVar('controller') . '/' . getVar('view'); ?>/";
            var limit = $('select[name=pagination_limit]').find(":selected").val();
            var account_id = $('select[name=pagination_account_id]').find(":selected").val();

            var parameter = '?page=1&limit=' + limit + '&account_id=' + account_id;

            window.location.replace(link + parameter);
        });
    });
</script>
<script type="text/javascript">

    $(document).ready(function() {
        $(document).on('click', '.import', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var display = $('#modal-import .modal-body');
            var redirect = "<?php echo getCurrentUrl(); ?>";

            $.ajax({
                url: '/underwriting/import-renewal-upload/',
                type: 'GET',
                data: {
                    redirect: redirect
                },
                beforeSend: function() {
                    display.html('');
                },
                success: function(data) {
                    $(data).appendTo(display);

                    $('#modal-import').modal('show');
                },
                error: function(xhr, desc, err) {
                    console.warn(xhr.responseText);
                }
            });
        });
    });
</script>