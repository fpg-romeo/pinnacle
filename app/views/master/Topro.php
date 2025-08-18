<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">TOPRO <span class="text-primary">[ List ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a href="$" class="btn btn-primary text-white" action="sync" id="syncBtn">
                    <i class="icon-base ti tabler-plus me-2"></i>
                    <span class="align-middle">Sync</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                                <select name="pagination_limit" class="form-select select pagination" data-parameter="limit" data-placeholder="Limit" autocomplete="off">
                                    <?php echo tool_dropdown_value(value_pagination_limit(), (getVar('limit') ? getVar('limit') : 10)); ?>
                                </select>
                            </div>
                            <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                <input name="pagination_keyword" type="text" class="form-control pd-x-10 pagination" data-parameter="keyword" placeholder="Search..." value="<?php echo getVar('keyword'); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php if (!empty($data['record']) && is_array($data['record'])) { ?>
                                        <?php
                                        foreach ($data['record'] as $record) {
                                        ?>
                                            <tr>
                                                <td><?= $record['code'] ?></td>
                                                <td><?= $record['description'] ?></td>
                                                <td><?= $record['is_active'] ? 'Active' : 'Inactive' ?></td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No topros found</td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>

                        <?php if (is_array($data['record'])) { ?>
                            <div class="row justify-content-between">
                                <div class="col-md-auto me-auto mt-8">
                                    <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                </div>

                                <div class="col-md-auto ms-auto mt-5">
                                    <ul class="pagination">
                                        <?php echo tool_pagination(getVar('page'), $data['total_page'], '/master/branch/', 'page', true); ?>
                                    </ul>
                                </div>

                            </div>
                        <?php } ?>

                        <div class="row justify-content-between">
                            <!-- <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
                                <div class="dt-info" aria-live="polite" id="DataTables_Table_0_info" role="status">
                                    Showing 1 to 10 of 100 entries
                                </div>
                            </div>
                            <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-5">
                                <div class="dt-paging">
                                    <nav aria-label="pagination">
                                        <ul class="pagination">
                                            <li class="dt-paging-button page-item disabled">
                                                <button class="page-link first" role="link" type="button" aria-controls="DataTables_Table_0" aria-disabled="true" aria-label="First" data-dt-idx="first" tabindex="-1">
                                                    <i class="icon-base ti tabler-chevrons-left scaleX-n1-rtl icon-18px"></i>
                                                </button>
                                            </li>
                                            <li class="dt-paging-button page-item disabled">
                                                <button class="page-link previous" role="link" type="button" aria-controls="DataTables_Table_0" aria-disabled="true" aria-label="Previous" data-dt-idx="previous" tabindex="-1">
                                                    <i class="icon-base ti tabler-chevron-left scaleX-n1-rtl icon-18px"></i>
                                                </button>
                                            </li>
                                            <li class="dt-paging-button page-item active">
                                                <button class="page-link" role="link" type="button" aria-controls="DataTables_Table_0" aria-current="page" data-dt-idx="0">1</button>
                                            </li>
                                            <li class="dt-paging-button page-item">
                                                <button class="page-link" role="link" type="button" aria-controls="DataTables_Table_0" data-dt-idx="1">2</button>
                                            </li>
                                            <li class="dt-paging-button page-item">
                                                <button class="page-link" role="link" type="button" aria-controls="DataTables_Table_0" data-dt-idx="2">3</button>
                                            </li>
                                            <li class="dt-paging-button page-item">
                                                <button class="page-link" role="link" type="button" aria-controls="DataTables_Table_0" data-dt-idx="3">4</button>
                                            </li>
                                            <li class="dt-paging-button page-item">
                                                <button class="page-link" role="link" type="button" aria-controls="DataTables_Table_0" data-dt-idx="4">5</button>
                                            </li>
                                            <li class="dt-paging-button page-item disabled">
                                                <button class="page-link ellipsis" role="link" type="button" aria-controls="DataTables_Table_0" aria-disabled="true" data-dt-idx="ellipsis" tabindex="-1">…</button>
                                            </li>
                                            <li class="dt-paging-button page-item">
                                                <button class="page-link" role="link" type="button" aria-controls="DataTables_Table_0" data-dt-idx="9">10</button>
                                            </li>
                                            <li class="dt-paging-button page-item">
                                                <button class="page-link next" role="link" type="button" aria-controls="DataTables_Table_0" aria-label="Next" data-dt-idx="next">
                                                    <i class="icon-base ti tabler-chevron-right scaleX-n1-rtl icon-18px"></i>
                                                </button>
                                            </li>
                                            <li class="dt-paging-button page-item">
                                                <button class="page-link last" role="link" type="button" aria-controls="DataTables_Table_0" aria-label="Last" data-dt-idx="last">
                                                    <i class="icon-base ti tabler-chevrons-right scaleX-n1-rtl icon-18px"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div> -->

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
                var keyword = encodeURIComponent($('input[name=pagination_keyword]').val());
                var parameter = '?page=1&limit=' + limit + '&keyword=' + keyword;

                window.location.replace(link + parameter);
            });
        });
    </script>

<script>
    $('#syncBtn').on('click', function(e) {
        e.preventDefault();


        var action = $(this).attr('action');

        $.ajax({
            url: '/master/topro_json',
            type: 'POST',
            dataType: 'json',


            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    alert('Sync successful!');
                    location.reload();
                } else {
                    alert('Sync failed: ' + response.message);
                }
            },
            error: function(xhr, status, error) {

                alert('An error occurred: ' + error + ' - ' + xhr.responseText);
            }
        });
    });
</script>