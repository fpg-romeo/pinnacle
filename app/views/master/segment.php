<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">Segment <span class="text-primary">[ List ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a class="btn btn-primary text-white showModal" action="add" data-bs-toggle="modal" data-bs-target="#segmentModal">
                    <i class="icon-base ti tabler-plus me-2"></i>
                    <span class="align-middle">Add Record</span>
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
                             <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                                <select name="pagination_status" class="form-control select pagination" data-placeholder="Status">
                                    <?php echo tool_dropdown_option($data['account_status'], (getVar('status') ? getVar('status') : 1), 'name'); ?>
                                </select>
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
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php if (!empty($data['segments']) && is_array($data['segments'])) { ?>
                                        <?php
                                        foreach ($data['segments'] as $segment) {
                                        ?>
                                            <tr>
                                                <td><?= $segment['code'] ?></td>
                                                <td><?= $segment['name'] ?></td>
                                                <td><?= $segment['is_active'] == 1 ? "Active" : "Inactive" ?></td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn px-3 py-1 dropdown-toggle btn-outline-secondary" data-bs-toggle="dropdown">
                                                            <i class="icon-base ti tabler-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item showModal" action="edit" data-bs-toggle="modal" data-bs-target="#segmentModal" data-id="<?= $segment['id'] ?>"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>
                                                            <a class="dropdown-item showModal" action="show" data-bs-toggle="modal" data-bs-target="#segmentModal" data-id="<?= $segment['id'] ?>"><i class="icon-base ti tabler-eye me-1"></i> Show</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No Segments found</td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>

                        <?php if (is_array($data['segments'])) { ?>
                            <div class="row justify-content-between">
                                <div class="col-md-auto me-auto mt-8">
                                    <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                </div>

                                <div class="col-md-auto ms-auto mt-5">
                                    <ul class="pagination">
                                        <?php echo tool_pagination(getVar('page'), $data['total_page'], '/master/segment/', 'page', true); ?>
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
    <div class="modal fade" id="segmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="post" id="segmentForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel3">Segment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12 mb-4">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" id="code" name="code" class="form-control" placeholder="Enter Code">
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12 mb-4">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name">
                            </div>
                        </div>
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-4 col-xs-12 mb-4">
                                <label for="is_active" class="form-label">Status</label>
                                <select id="is_active" class="form-control" name="is_active" data-style="btn-default">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" value="" name="action">
                        <input type="hidden" value="" name="id">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary submit">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript">
        //DATATABLE FILTER
        $(document).ready(function() {
            $('.pagination').bind('blur change', function(e) {
                e.preventDefault();

                var link = "/<?php echo getVar('controller') . '/' . getVar('view'); ?>/";
                var limit = $('select[name=pagination_limit]').find(":selected").val();
                var status = $('select[name=pagination_status]').find(":selected").val();
                var keyword = encodeURIComponent($('input[name=pagination_keyword]').val());
                var parameter = '?page=1&limit=' + limit + '&keyword=' + keyword + '&status=' + status;

                window.location.replace(link + parameter);
            });
        });
    </script>

    <script>
        $('.showModal').click(function(e) {

            e.preventDefault();
            $('#segmentModal').find('form')[0].reset();
            $('#segmentModal').find('input').prop('readonly', false);
            $('#segmentModal').find('select').prop('disabled', false);
            var action = $(this).attr('action');
            $('[name="action"]').val(action);
            $('.submit').css('display', 'block');

            if (action != 'add') {
                $.ajax({
                    url: '/master/segment_json/',
                    method: 'POST',
                    data: {
                        id: $(this).data('id')
                    },
                    success: function(data) {
                        $('[name="id"]').val(data.id);
                        $('#code').val(data.code);
                        $('#name').val(data.name);
                        $('#is_active').val(data.is_active);
                    }
                });

                if (action == "show") {
                    $('#segmentModal').find('input').prop('readonly', true);
                    $('#segmentModal').find('select').prop('disabled', true);
                    $('.submit').css('display', 'none');
                }
            }
        });
    </script>