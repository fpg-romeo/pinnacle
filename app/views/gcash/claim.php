    <div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">GCash</a>
                <span class="breadcrumb-item active">Records</span>
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
            <div class="br-section-wrapper pd-0">
                <div class="card">
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
                                                <th class="wd-5p">BATCH NUMBER</th>
                                                <th class="wd-10p">WORKFLOW NUMBER</th>
                                                <th class="wd-10p">ENDORSEMENT NUMBER</th>
                                                <th class="wd-10p">POLICY ID<br>(GCASH)</th>
                                                <th class="wd-15p">FULL NAME</th>
                                                <th class="wd-5p tx-center">CONTACT NO</th>
                                                <th class="wd-10p tx-center">DATE OF INSURANCE<br>START</th>
                                                <th class="wd-10p tx-center">DATE OF INSURANCE<br>END</th>
                                                <th class="wd-10p tx-center">MANAGED<br>BY</th>
                                                <th class="wd-10p tx-center">MANAGED<br>DATE</th>
                                                <th class="wd-5p tx-center">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (isset($data['records']) && !empty($data['records'])) {
                                                foreach ($data['records'] as $key => $value) {
                                                    echo '
                                                                <tr id="' . $value['id'] . '">
                                                                    <td>' . ucwords(htmlDecode($value['batch_number'])) . '</td>
                                                                    <td>' . htmlDecode($value['workflow_number']) . '</td>
                                                                    <td>' . htmlDecode($value['endorsement_number']) . '</td>
                                                                    <td>' . htmlDecode($value['policy_id']) . '</td>
                                                                    <td>' . htmlDecode($value['first_name']) . ' ' . htmlDecode($value['middle_name']) . ' ' . htmlDecode($value['last_name']) . '</td> 
                                                                    <td class="tx-center">' . htmlDecode($value['mobile_number']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['date_insurance_start']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['date_insurance_end']) . '</td>
                                                                    <td class="tx-center">' . htmlDecode($value['account_name']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['created_when']) . '</td>
                                                                    <td class="tx-center"> 
                                                                        <div class="dropdown d-inline-block">
                                                                            <a href="" class="tx-gray-800 d-inline-block" data-toggle="dropdown">
                                                                                <div class="pd-x-5 bd d-flex align-items-center justify-content-center">
                                                                                    <span><i class="fa fa-cog"></i></span>
                                                                                    <i class="fa fa-angle-down mg-l-10"></i>
                                                                                </div>
                                                                            </a>
                                                                            <div class="dropdown-menu pd-5">
                                                                                <nav class="nav nav-style-2 flex-column">
                                                                                    <a data-id="'.idEncrypt(htmlDecode($value['id'])).'" class="nav-link view" data-action="view" title="View Record"><i class="fa fa-file-text-o"></i> Details</a>
                                                                                    <a data-id="'.idEncrypt(htmlDecode($value['id'])).'" class="nav-link manage" data-action="update" title="Update Details"><i class="fa fa-pencil-square-o"></i> Update</a>
                                                                                    <a data-id="'.idEncrypt(htmlDecode($value['id'])).'" class="nav-link delete" data-action="delete" data-title="'.htmlDecode($value['batch_number']).'" title="Delete Record"><i class="fa fa-trash"></i> Delete</a>  
                                                                                </nav>
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            ';
                                                }
                                            } else {
                                                echo '<tr><td colspan="11" class="tx-center">No record found</td></tr>';
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
        <div class="modal-dialog modal-dialog-vertical-center modal-lg" role="document">
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
                var keyword = encodeURIComponent($('input[name=pagination_keyword]').val());

                var parameter = '?page=1&limit=' + limit + '&keyword=' + keyword;

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
                    url: '/gcash/import-claim-upload/',
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
                url: '/gcash/import-claim-view/',
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