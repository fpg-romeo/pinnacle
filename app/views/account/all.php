<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">Records <span class="text-primary">[ List ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a class="btn btn-primary text-white showModal" action="add" data-bs-toggle="modal" data-bs-target="#accountModal">
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
                                <select name="pagination_limit" class="form-control select pagination" data-parameter="limit" data-placeholder="Limit" autocomplete="off">
                                    <?php echo tool_dropdown_value(value_pagination_limit(), (getVar('limit') ? getVar('limit') : 10)); ?>
                                </select>
                            </div>
                            <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                                <input name="pagination_keyword" type="text" class="form-control pd-x-10 pagination" data-parameter="keyword" placeholder="Search..." value="<?php echo getVar('keyword'); ?>">
                            </div>
                            <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                                <select name="pagination_department" class="form-control select pagination" data-placeholder="Department">
                                    <option value="all">All</option>
                                    <?php echo tool_dropdown_option($data['account_department'], (getVar('department') ? getVar('department') : 1), 'name'); ?>
                                </select>
                            </div>
                            <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                                <select name="pagination_status" class="form-control select pagination" data-placeholder="Status">
                                    <option value="all">All</option>
                                    <?php echo tool_dropdown_option($data['account_status'], (getVar('status') ? getVar('status') : 0), 'name'); ?>
                                </select>
                            </div>
                            <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                                <a href="/account/all/1" type="button" class="btn btn-info mg-l-2 reset">
                                    <i class="fa fa-refresh fa-lg"></i> <small>RESET</small>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-2">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="wd-5p tx-center">PHOTO</th>
                                        <th class="wd-20p">NAME</th>
                                        <th class="wd-15p">ACTIVE DIRECTORY</th>
                                        <th class="wd-20p">EMAIL / CONTACT NO.</th>
                                        <th class="wd-20p">DEPARTMENT</th>
                                        <th class="wd-10p">TYPE</th>
                                        <th class="wd-5p tx-center">STATUS</th>
                                        <th class="wd-5p tx-center">
                                            <center>ACTION</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php if (!empty($data['user']) && is_array($data['user'])) { ?>
                                        <?php foreach ($data['user'] as $key => $value) { ?>
                                            <tr id=<?= $value['id'] ?>>
                                                <td class="tx-center cursor-pointer view-image" data-photo="<?= htmlDecode($value['photo']) ?>" data-name="<?= htmlDecode($value['first_name']) . ' ' . htmlDecode($value['last_name']) ?>">
                                                    <img src="<?= displayImage(thumbnailName(htmlDecode($value['photo'])), 'account') ?>" class="img-fluid">
                                                </td>
                                                <td> <?= $value['first_name'] . ' ' . (!empty($value['middle_name']) ? $value['middle_name'] . ' ' : '') . $value['last_name'] ?> </td>
                                                <td><?= $value['active_directory'] ?></td>
                                                <td><?= $value['email'] ?></td>
                                                <td><?= $value['account_department_name'] ?></td>
                                                <td><?= $value['account_role_name'] ?> </td>
                                                <td class="tx-center"><?= $value['account_status_name'] ?> </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button type="button" class="btn px-3 py-1 dropdown-toggle btn-outline-secondary" data-bs-toggle="dropdown">
                                                            <i class="icon-base ti tabler-settings"></i>
                                                        </button>
                                                        <div class="dropdown-menu">
                                                            <a class="dropdown-item showModal" action="edit" data-bs-toggle="modal" data-bs-target="#accountModal" data-id="<?= $value['id'] ?>"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="4" class="text-center">No Profile found</td>
                                        </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                        <?php if (is_array($data['user'])) { ?>
                            <div class="row mg-t-40">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 lh-22">
                                    <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <ul class="pagination mg-0 float-right">
                                        <?php echo tool_pagination(getVar('page'), $data['total_page'], '/account/all/', 'page', true); ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="accountModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <form method="post" id="branchForm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel3">Record</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-6">
                                    <div class="col-md-4">
                                        <label class="form-label" for="multicol-first-name">First Name</label>
                                        <input name="first_name" type="text" id="first_name" class="form-control" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="multicol-middle-name">Middle Name</label>
                                        <input name="middle_name" type="text" id="middle_name" class="form-control" />
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="multicol-last-name">Last Name</label>
                                        <input name="last_name" type="text" id="last_name" class="form-control" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="multicol-username">Username</label>
                                        <input name="active_directory" type="text" id="active_directory" class="form-control" placeholder="P65XXXXX" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="multicol-email">Email</label>
                                        <input name="email" type="text" id="email" class="form-control" aria-describedby="multicol-email2" />
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="multicol-birthdate">Role</label>
                                        <select name="account_role_id" id="account_role_id" class="form-control select" data-placeholder="Status">
                                            <?php echo tool_dropdown_option($data['account_role'], null, 'name'); ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="multicol-status">Status</label>
                                        <select name="account_status_id" id="account_status_id" class="form-control select" data-placeholder="Status">
                                            <?php echo tool_dropdown_option($data['account_status'], null, 'name'); ?>
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <input type="hidden" value="" name="action">
                                        <input type="hidden" value="" name="id">
                                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary submit">Save Changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
                var status = $('select[name=pagination_status]').find(":selected").val();
                var keyword = encodeURIComponent($('input[name=pagination_keyword]').val());
                var department = $('select[name=pagination_department]').find(":selected").val();

                var parameter = '?page=1&limit=' + limit + '&keyword=' + keyword + '&department=' + department + '&status=' + status;

                window.location.replace(link + parameter);
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.view-image', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                e.stopPropagation();
                var photo = $(this).data('photo');
                var name = $(this).data('name');
                var display = $('#modal-view-image .modal-body');
                display.html('');
                if (photo == '') {
                    $('<img src="' + photo + '" class="img-fluid">').appendTo(display);
                } else {
                    $('<img src="' + photo + '" class="img-fluid">').appendTo(display);
                }
                $('#modal-view-image .modal-title span').html(name);
                $('#modal-view-image').modal('show');
            });
        });
    </script>

    <script>
        $('.showModal').click(function(e) {

            e.preventDefault();
            $('#accountModal').find('form')[0].reset();
            $('#accountModal').find('input').prop('readonly', false);
            $('#accountModal').find('select').prop('disabled', false);
            var action = $(this).attr('action');
            $('[name="action"]').val(action);
            $('.submit').css('display', 'block');

            if (action == "edit") {
                var id = $(this).data('id');
                console.log("📤 Sending to /account/add_json/:", {
                    id: id
                });

                $.ajax({
                    url: '/account/all_json/',
                    method: 'POST',
                    data: {
                        id: $(this).data('id')
                    },
                    success: function(data) {
                        console.log("✅ Response from server:", data);
                        $('[name="id"]').val(data.id);
                        $('#first_name').val(data.first_name);
                        $('#middle_name').val(data.middle_name);
                        $('#last_name').val(data.last_name);
                        $('#active_directory').val(data.active_directory);
                        $('#email').val(data.email);
                        $('#account_role_id').val(data.account_role_id);
                        $('#account_status_id').val(data.account_status_id);
                    },
                    error: function(xhr, status, error) {
                        console.error("❌ AJAX Error:", error);
                        console.log("Response Text:", xhr.responseText);
                    }
                });

                if (action == "show") {
                    $('#accountModal').find('input').prop('readonly', true);
                    $('#accountModal').find('select').prop('disabled', true);
                    $('.submit').css('display', 'none');
                }
            }
        });
    </script>