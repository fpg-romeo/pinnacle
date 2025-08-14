<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">Notification Email</h4>
                <p class="mb-0">
                    Automation Email Sender | 
                    <span class="notification-email-status-unprocessed"><i class="fa fa-circle" aria-hidden="true"></i> UNPROCESSED</span>
                    <span class="notification-email-status-queue"><i class="fa fa-circle" aria-hidden="true"></i> QUEUE</span>
                    <span class="notification-email-status-error"><i class="fa fa-circle" aria-hidden="true"></i> ERROR</span>
                    <span class="notification-email-status-failed"><i class="fa fa-circle" aria-hidden="true"></i> FAILED</span>
                </p>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a href="/cron/test-email/" class="btn btn-info text-white">
                    <i class="icon-base ti tabler-send me-2"></i>
                    <span class="align-middle">Send Test Email</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                                <select id="form-repeater-1-3" class="form-select">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                            <div class="mb-6 col-lg-6 col-xl-3 col-12 mb-0">
                                <input type="text" id="form-repeater-1-1" class="form-control" placeholder="Search..." />
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-2">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>TYPE / SUBJECT</th>
                                        <th>RECIPIENT TO</th>
                                        <th>RECIPIENT CC</th>
                                        <th>RECIPIENT BCC</th>
                                        <th>PROCESS BY</th>
                                        <th>ATTEMPT</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php
                                        if(is_array($data['email'])){
                                            foreach($data['email'] as $key => $value){
                                                echo '
                                                    <tr id="'.$value['id'].'" class="notification-email-status-'.strtolower(htmlDecode($value['status_name'])).'">
                                                        <td>
                                                            <small class="text-muted">
                                                                ( '.htmlDecode($value['id']).' -  '.htmlDecode($value['template']).' )
                                                            </small>
                                                            <br>
                                                            '.htmlDecode($value['name']).'
                                                            <br>
                                                            '.htmlDecode($value['subject']).'
                                                        </td>
                                                        <td>'.htmlDecode($value['recipient_to']).'</td>
                                                        <td>'.htmlDecode($value['recipient_cc']).'</td>
                                                        <td>'.htmlDecode($value['recipient_bcc']).'</td>
                                                        <td class="tx-center">
                                                            '.htmlDecode($value['account_name']).'<br>
                                                            <span class="text-muted">
                                                                '.dateReformat($value['created_when'], 'd-M-Y').'<br>
                                                                '.dateReformat($value['created_when'], 'h:i A').'
                                                            </span>
                                                        </td>
                                                        <td class="tx-center">'.htmlDecode($value['attempt']).'</td>
                                                        <td class="tx-center">
                                                            '.htmlDecode($value['status_name']).'
                                                            <br>
                                                    ';

                                                if(!empty($value['updated_when'])){
                                                echo '

                                                            <span class="text-muted">
                                                                '.dateReformat($value['updated_when'], 'd-M-Y').'<br>
                                                                '.dateReformat($value['updated_when'], 'h:i A').'
                                                            </span>
                                                            <br>
                                                    ';
                                                }

                                                echo '
                                                            <div class="dropdown">
                                                                <button type="button" class="btn px-3 py-1 dropdown-toggle btn-outline-secondary" data-bs-toggle="dropdown">
                                                                    <i class="icon-base ti tabler-settings"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item view" href="javascript:void(0);" data-action="view" data-id="'.idEncrypt($value['id']).'"><i class="icon-base ti tabler-pencil me-1"></i> Update</a>
                                                                </div>
                                                            </div>

                                                            <div class="dropdown d-inline-block">
                                                                <a href="" class="tx-gray-800 d-inline-block" data-toggle="dropdown">
                                                                    <div class="pd-x-5 bd d-flex align-items-center justify-content-center">
                                                                        <span><i class="fa fa-cog"></i></span>
                                                                        <i class="fa fa-angle-down mg-l-10"></i>
                                                                    </div>
                                                                </a>
                                                                <div class="dropdown-menu pd-5">
                                                                    <nav class="nav nav-style-2 flex-column">
                                                                        <a class="nav-link people view" data-action="view" data-id="'.idEncrypt($value['id']).'"><i class="fa fa-pencil-square-o"></i> Update</a>
                                                                    </nav>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                ';
                                            }
                                        }else{
                                            echo '<tr><td colspan="7" class="text-center">No record found</td></tr>';
                                        }
                                    ?> 
                                    <!--
                                    <tr>
                                        <td>
                                            <i class="icon-base ti tabler-brand-angular icon-md text-danger me-4"></i>
                                            <span class="fw-medium">Angular Project</span>
                                        </td>
                                        <td>Albert Cook</td>
                                        <td>
                                            <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Lilian Fuller">
                                                    <img src="/public/img/avatars/5.png" alt="Avatar" class="rounded-circle" />
                                                </li>
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                                                    <img src="/public/img/avatars/6.png" alt="Avatar" class="rounded-circle" />
                                                </li>
                                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" class="avatar avatar-xs pull-up" title="Christina Parker">
                                                    <img src="/public/img/avatars/7.png" alt="Avatar" class="rounded-circle" />
                                                </li>
                                            </ul>
                                        </td>
                                        <td><span class="badge bg-label-primary me-1">Active</span></td>
                                        <td >
                                            <div class="dropdown">
                                                <button type="button" class="btn px-3 py-1 dropdown-toggle btn-outline-secondary" data-bs-toggle="dropdown">
                                                    <i class="icon-base ti tabler-settings"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>
                                                    <a class="dropdown-item" href="javascript:void(0);"><i class="icon-base ti tabler-trash me-1"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    -->
                                </tbody>
                            </table>
                        </div>

                        <?php if(is_array($data['email'])){ ?>
                            <div class="row justify-content-between">
                                <div class="d-md-flex justify-content-between align-items-center col-md-auto me-auto mt-0">
                                    <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                </div>


                                
                            </div>

                            <div class="row mg-t-40">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 lh-22">
                                    <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                    <ul class="pagination mg-0 float-right">
                                        <?php echo tool_pagination(getVar('page'), $data['total_page'], '/notification/email/', 'page', true); ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>



                        <div class="row justify-content-between">
                            <div class="d-md-flex justify-content-between align-items-center col-md-auto me-auto mt-0">
                                Showing 1 to 10 of 100 entries 
                            </div>
                            <div class="d-md-flex justify-content-between align-items-center col-md-auto ms-auto mt-5">
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
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
    
    
    
    <!--
    <div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">Control Panel</a>
                <a class="breadcrumb-item" href="/">Email</a>
                <span class="breadcrumb-item active">Records</span>
            </nav>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-envelope"></i>
            <div>
                <h4>Notification Email</h4>
                <p class="mg-b-0">
                    Automation Email Sender | 
                    <span class="notification-email-status-unprocessed"><i class="fa fa-circle" aria-hidden="true"></i> UNPROCESSED</span>
                    <span class="notification-email-status-queue"><i class="fa fa-circle" aria-hidden="true"></i> QUEUE</span>
                    <span class="notification-email-status-error"><i class="fa fa-circle" aria-hidden="true"></i> ERROR</span>
                    <span class="notification-email-status-failed"><i class="fa fa-circle" aria-hidden="true"></i> FAILED</span>
                </p>
                <div class="pagetitle-button">
                    <a href="/cron/test-email/" class="btn btn-info add mg-l-15" target="_blank">
                        <i class="fa fa-paper-plane-o fa-lg"></i> <small>SEND TEST EMAIL</small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php flash(promptMessage('message')); ?>
        </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">
                <div class="row mg-b-20">
                        <div class="col-xs-12 col-sm-12 col-md-2 col-lg-1">
                            <select name="pagination_limit" class="form-control select pagination" data-parameter="limit" data-placeholder="Limit" autocomplete="off">
                                <?php echo tool_dropdown_value(value_pagination_limit(), (getVar('limit') ? getVar('limit') : 10)); ?>
                            </select>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-3">
                            <input name="pagination_keyword" type="text" class="form-control pd-x-10 pagination" data-parameter="keyword" placeholder="Search..." value="<?php echo getVar('keyword'); ?>">
                        </div>
                </div>
                <div class="table-wrapper">
                    <table class="table table-striped table-bordered bd mg-0 table-responsive d-md-table">
                        <thead class="thead-colored thead-dark">
                            <tr>
                                <th class="wd-30p">TYPE / SUBJECT</th>
                                <th class="wd-15p">RECIPIENT TO</th>
                                <th class="wd-15p">RECIPIENT CC</th>
                                <th class="wd-15p">RECIPIENT BCC</th>
                                <th class="wd-10p tx-center">PROCESS BY</th>
                                <th class="wd-5p tx-center">ATTEMPT</th>
                                <th class="wd-10p tx-center">STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(is_array($data['email'])){
                                    foreach($data['email'] as $key => $value){
                                        echo '
                                            <tr id="'.$value['id'].'" class="notification-email-status-'.strtolower(htmlDecode($value['status_name'])).'">
                                                <td>
                                                    <small class="text-muted">
                                                        ( '.htmlDecode($value['id']).' -  '.htmlDecode($value['template']).' )
                                                    </small>
                                                    <br>
                                                    '.htmlDecode($value['name']).'
                                                    <br>
                                                    '.htmlDecode($value['subject']).'
                                                </td>
                                                <td>'.htmlDecode($value['recipient_to']).'</td>
                                                <td>'.htmlDecode($value['recipient_cc']).'</td>
                                                <td>'.htmlDecode($value['recipient_bcc']).'</td>
                                                <td class="tx-center">
                                                    '.htmlDecode($value['account_name']).'<br>
                                                    <span class="text-muted">
                                                        '.dateReformat($value['created_when'], 'd-M-Y').'<br>
                                                        '.dateReformat($value['created_when'], 'h:i A').'
                                                    </span>
                                                </td>
                                                <td class="tx-center">'.htmlDecode($value['attempt']).'</td>
                                                <td class="tx-center">
                                                    '.htmlDecode($value['status_name']).'
                                                    <br>
                                             ';

                                        if(!empty($value['updated_when'])){
                                        echo '

                                                    <span class="text-muted">
                                                        '.dateReformat($value['updated_when'], 'd-M-Y').'<br>
                                                        '.dateReformat($value['updated_when'], 'h:i A').'
                                                    </span>
                                                    <br>
                                             ';
                                        }

                                        echo '
                                                    <div class="dropdown d-inline-block">
                                                        <a href="" class="tx-gray-800 d-inline-block" data-toggle="dropdown">
                                                            <div class="pd-x-5 bd d-flex align-items-center justify-content-center">
                                                                <span><i class="fa fa-cog"></i></span>
                                                                <i class="fa fa-angle-down mg-l-10"></i>
                                                            </div>
                                                        </a>
                                                        <div class="dropdown-menu pd-5">
                                                            <nav class="nav nav-style-2 flex-column">
                                                                <a class="nav-link people view" data-action="view" data-id="'.idEncrypt($value['id']).'"><i class="fa fa-pencil-square-o"></i> Update</a>
                                                            </nav>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        ';
                                    }
                                }else{
                                    echo '<tr><td colspan="7" class="tx-center wd-100p">No record found</td></tr>';
                                }
                            ?> 
                        </tbody>
                    </table>
                </div>
                <?php if(is_array($data['email'])){ ?>
                    <div class="row mg-t-40">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 lh-22">
                            <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <ul class="pagination mg-0 float-right">
                                <?php echo tool_pagination(getVar('page'), $data['total_page'], '/notification/email/', 'page', true); ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div id="modal" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center modal-lg" role="document">
            <form id="form">
                <div class="modal-content bd-0">
                    <div class="modal-header pd-y-20 pd-x-25">
                        <h6 class="tx-14 mg-b-0 tx-uppercase tx-primary tx-bold modal-title">Manage Record</h6>
                        <button type="button" class="close cursor-pointer" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body pd-25">
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Type</label>
                            <label class="col-sm-9 tx-bold name"></label>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Subject</label>
                            <label class="col-sm-9 tx-bold subject"></label>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Recipient To</label>
                            <label class="col-sm-9 tx-bold recipient_to"></label>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Recipient CC</label>
                            <label class="col-sm-9 tx-bold recipient_cc"></label>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Recipient BCC</label>
                            <label class="col-sm-9 tx-bold recipient_bcc"></label>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Process By</label>
                            <label class="col-sm-9 tx-bold">
                                <span class="account_name"></span><br>
                                <span class="created_when"></span>
                            </label>
                        </div>
                        <div class="row mg-b-10">
                            <label class="col-sm-3">Status</label>
                            <label class="col-sm-9 tx-bold">
                                <span class="response"></span><br>
                                <span class="updated_when"></span>
                            </label>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 form-control-label">Action</label>
                            <div class="col-sm-4 mg-t-10 mg-sm-t-0">
                                <select name="status_id" class="form-control select" required>
                                    <?php echo tool_dropdown_value(value_status_email(), '', 'array'); ?>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="row mg-t-30">
                            <label class="col-sm-3">Email Template</label>
                            <label class="col-sm-9 tx-bold template"></label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <center>
                            <input name="id" type="hidden" class="form-control">
                            <button type="submit" class="btn btn-primary w-150px submit"><small>SUBMIT</small></button>
                        </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
    -->
    
    <script type="text/javascript">
        //DATATABLE FILTER
        $(document).ready(function(){
            $('.pagination').bind('blur change',function(e){
                e.preventDefault();

                var link      = "/<?php echo getVar('controller').'/'.getVar('view'); ?>/";
                var limit     = $('select[name=pagination_limit]').find(":selected").val();
                var keyword   = encodeURIComponent($('input[name=pagination_keyword]').val());
                var parameter = '?page=1&limit='+limit+'&keyword='+keyword;
                
                window.location.replace(link+parameter);
            });
        });
    </script>

    <script type="text/javascript">
        //SUBMIT
        $(document).on('click', '.submit', function(e){
            e.preventDefault();
            
            $('.required').remove();

            $('#form input, #form select').each(
                function(index){  
                    var input   = $(this);
                    var prop    = input.prop("required");
                    var name    = input.prop("name");
                    var type    = input.prop("type");
                    var value   = input.val();
                    var parent  = input.parent();

                    if (typeof prop !== typeof undefined && prop !== false) {
                        if(value == ''){
                            parent.append('<i class="required">required field</i>');
                        }
                    }
                }
            );

            if($('#form .required').length <= 0) {
                $.ajax({
                    url: '/notification/email-json/',
                    type: 'POST',
                    data: $('#form').serialize(),
                    beforeSend: function(){
                        promptAjaxLoading('form');
                    },
                    success: function(data){
                        promptAjaxSuccess('modal', data.message, 'reload');
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
               });
            }
        });

        //VIEW
        $(document).ready(function(){
            $(document).on('click', '.view', function(e){
                e.preventDefault();
                var id      = $(this).data('id');
                var action  = $(this).data('action');

                $.ajax({
                    url: '/notification/email-json/',
                    type: 'POST',
                    data: {id:id, action:action},
                    success: function(data){
                        $('.name').html(data.name);
                        $('.template').html(data.template);
                        $('.subject').html(data.subject);
                        $('.recipient_to').html(data.recipient_to);
                        $('.recipient_cc').html(data.recipient_cc);
                        $('.recipient_bcc').html(data.recipient_bcc);
                        $('.account_name').html(data.account_name);
                        $('.created_when').html(data.created_when);
                        $('.updated_when').html(data.updated_when);
                        $('.response').html(data.response);
                        $('.status_name').html(data.status_name);
                        $('input[name=id]').val(data.id);
                        $('select[name=status_id]').val(data.status_id).find("option[value=" + data.status_id +"]").attr('selected', true);

                        $('#modal').modal('show');
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
               });
            });
        });
    </script>