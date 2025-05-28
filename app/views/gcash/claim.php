    <div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">Claims</a>
                <a class="breadcrumb-item" href="/">GCash</a>
                <span class="breadcrumb-item active">All Records</span>
            </nav>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-wpforms"></i>
            <div>
                <h4>GCash <b class="tx-primary">[ Records ]</b></h4>
                <p class="mg-b-0"></p>
                <div class="pagetitle-button">
                    <button type="button" class="btn btn-info mg-l-15 import">
                        <i class="fa fa-cloud-upload fa-lg"></i> <small>IMPORT</small>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php flash(promptMessage('message')); ?>
        </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper" id="list">
                <div class="card bd">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs tx-bold">
                            <li class="nav-item">
                                <a href="/gcash/claim-summary/1/" class="nav-link tab-link">SUMMARY</a>
                            </li>
                            <li class="nav-item">
                                <a href="/gcash/claim/1/" class="nav-link tab-link active">ALL RECORDS</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body color-gray-lighter">
                        <div class="tab-content">

                            <div class="tab-pane active" id="record">
                                <div class="row mg-b-20">
                                    <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
                                        <select name="pagination_limit" class="form-control select pagination" data-parameter="limit" data-placeholder="Limit">
                                            <?php echo tool_dropdown_value(value_pagination_limit(), (getVar('limit') ? getVar('limit') : 10)); ?>
                                        </select>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 hidden">
                                        <select name="pagination_account_id" class="form-control select pagination" data-placeholder="Filter By Account">
                                            <?php
                                            if (isset($data['accounts']) && count($data['accounts']) > 1) {
                                                echo '<option value="all" ' . (getVar('account_id') == 'all' ? 'selected' : "") . '>All</option>';
                                            }
                                            ?>
                                            <?php echo tool_dropdown_option($data['accounts'], (getVar('account_id') ? getVar('account_id') : ''), 'full_name'); ?>
                                        </select>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                        <input name="pagination_keyword" type="text" class="form-control pd-x-10 pagination" data-parameter="keyword" placeholder="Search..." value="<?php echo getVar('keyword'); ?>">
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                        <a href="/gcash/claim/1" type="button" class="btn btn-info mg-l-2 reset">
                                            <i class="fa fa-refresh fa-lg"></i> <small>RESET</small>
                                        </a>
                                    </div>
                                </div>
                                <div class="table-responsive bd rounded">
                                    <table class="table table-striped table-bordered table-hover mg-b-0">
                                        <thead class="thead-colored thead-dark">
                                            <tr>
                                                <th class="wd-6p">BATCH NUMBER</th>
                                                <th class="wd-6p">WORKFLOW NUMBER</th>
                                                <th class="wd-6p">ENDORSEMENT NUMBER</th>
                                                <th class="wd-7p">POLICY ID<br>(GCASH)</th>
                                                <th class="wd-12p">FULL NAME</th>
                                                <th class="wd-7p tx-center">CONTACT NO</th>
                                                <th class="wd-7p tx-center">DATE OF BIRTH</th>
                                                <th class="wd-7p">EMAIL ADDRESS</th>
                                                <th class="wd-7p tx-center">DATE OF INSURANCE<br>START</th>
                                                <th class="wd-7p tx-center">DATE OF INSURANCE<br>END</th>
                                                <th class="wd-7p tx-center">UPLOADED<br>BY</th>
                                                <th class="wd-7p tx-center">UPLOADED<br>WHEN</th>
                                                <th class="wd-5p tx-center">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($data['records']) && !empty($data['records'])) {
                                                foreach ($data['records'] as $key => $value) {
                                                    $action = '
                                                                    <div class="dropdown d-inline-block">
                                                                        <a href="" class="tx-gray-800 d-inline-block" data-toggle="dropdown">
                                                                            <div class="pd-x-5 bd d-flex align-items-center justify-content-center">
                                                                                <span><i class="fa fa-cog"></i></span>
                                                                                <i class="fa fa-angle-down mg-l-10"></i>
                                                                            </div>
                                                                        </a>
                                                                        <div class="dropdown-menu pd-5">
                                                                            <nav class="nav nav-style-2 flex-column">
                                                                                <a data-id="' . idEncrypt(htmlDecode($value['id'])) . '"   class="nav-link view" data-action="view" title="View Record"><i class="fa fa-file-text-o"></i> Details</a>
                                                                                
                                                                            </nav>
                                                                        </div>
                                                                    </div>
                                                            ';

                                                    echo '
                                                                <tr id="' . $value['id'] . '">
                                                                    <td>' . htmlDecode($value['batch_number']) . '</td>
                                                                    <td>' . htmlDecode($value['workflow_number']) . '</td>
                                                                    <td>' . htmlDecode($value['endorsement_number']) . '</td>
                                                                    <td>' . htmlDecode($value['policy_id']) . '</td>
                                                                    <td>' . htmlDecode($value['first_name']) . '</td> 
                                                                    <td class="tx-center">' . htmlDecode($value['mobile_number']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['date_of_birth']) . '</td>
                                                                    <td>' . htmlDecode($value['email_address']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['date_insurance_start']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['date_insurance_end']) . '</td>
                                                                    <td class="tx-center">' . htmlDecode($value['uploader_name']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['created_when']) . '</td>
                                                                    <td class="tx-center"> 
                                                                        ' . $action . '
                                                                    </td>
                                                                </tr>
                                                            ';
                                                }
                                            } else {
                                                echo '<tr><td colspan="10" class="tx-center">No record found</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (isset($data['records']) && !empty($data['records'])) { ?>
                                    <div class="row mg-t-40">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 lh-22">
                                            <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <ul class="pagination mg-0 float-right">
                                                <?php echo tool_pagination(getVar('page'), $data['total_page'], '/gcash/claim/', 'page', true); ?>
                                            </ul>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-import" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center modal-xx" role="document">
            <div class="modal-content bd-0">
                <div class="modal-body pd-25">

                </div>
            </div>
        </div>
    </div>

    <div id="modal-view" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center modal-xl" role="document">
            <div class="modal-content bd-0">
                <div class="modal-body pd-25">

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
                var keyword = encodeURIComponent($('input[name=pagination_keyword]').val());

                var parameter = '?page=1&limit=' + limit + '&keyword=' + keyword + '&account_id=' + account_id;

                window.location.replace(link + parameter);
            });
        });
    </script>

    <script type="text/javascript">
        //IMPORT
        $(document).ready(function() {
            $(document).on('click', '.import', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var display = $('#modal-import .modal-body');
                var redirect = "<?php echo getCurrentUrl(); ?>";

                $.ajax({
                    url: '/gcash/import-claim/',
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

        $(document).on('click', '.view', function(e) {
            e.preventDefault();
            var id = $(this).data('id');

            var display = $('#modal-view .modal-body');
            $.ajax({
                url: '/gcash/import-view/',
                type: 'GET',
                data: {
                    account_id: id
                },
                beforeSend: function() {
                    display.html('');
                },
                success: function(data) {
                    // console.log('success');
                    $(data).appendTo(display);

                    $('#modal-view').modal('show');
                },
                error: function(xhr, desc, err) {
                    //console.log(xhr);
                    console.warn(xhr.responseText);
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.delete-encode', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var id = $(this).data('company_encode');
                var action = $(this).data('action');
                var display = $('#modal-encode .modal-body');

                if (confirm("Are you sure you want to delete this record?")) {
                    $.ajax({
                        url: '/company/manage-encode-json/',
                        type: 'POST',
                        data: {
                            id: id,
                            action: action
                        },
                        success: function(data) {
                            alert(data.message);
                            window.location = document.URL;
                        },
                        error: function(xhr, desc, err) {
                            console.warn(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).on('click', '.tab-link', function(e) {
                $('select[name=pagination_limit]').val(10);
                $('.pagination').trigger('change');
            });
        });
    </script>