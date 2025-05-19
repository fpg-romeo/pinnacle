<div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">Company Encode</a>
                <span class="breadcrumb-item active">Data Entry</span>
            </nav>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-database"></i>
            <div>
                <h4>Data Entry <b class="tx-primary">[ Records ]</b></h4>
                <p class="mg-b-0"></p>
                <div class="pagetitle-button">
                    <!--
                    <button type="button" class="btn btn-info mg-l-15 import-bulk">
                        <i class="fa fa-cloud-upload fa-lg"></i> <small>BULK IMPORT</small>
                    </button>
                    -->
                    <button type="button" class="btn btn-info mg-l-15 import">
                        <i class="fa fa-cloud-upload fa-lg"></i> <small>IMPORT</small>
                    </button>
                    <a href="" class="btn btn-info mg-l-15 add-encode" data-action="add">
                        <i class="fa fa-plus-circle fa-lg"></i> <small>ADD RECORD</small>
                    </a>
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
                                <a href="/company/encode-summary/1/" class="nav-link tab-link">SUMMARY</a>
                            </li>
                            <li class="nav-item">
                                <a href="/company/encode/1/" class="nav-link tab-link active">ALL RECORDS</a>
                            </li>
                            <?php if(ACCOUNT_TYPE_ID == 1 || in_array(ACCOUNT_LEVEL_ID,$data['default_level_leader'])){ ?>
                                <li class="nav-item">
                                    <a href="/company/encode-duplicate/1/" class="nav-link tab-link">DUPLICATES</a>
                                </li>
                            <?php } ?>
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
                                    <?php if(ACCOUNT_TYPE_ID != $data['account_type_encoder']){ ?>
                                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 hidden">
                                            <select name="pagination_account_id" class="form-control select pagination" data-placeholder="Filter By Account">
                                                <?php 
                                                        if(isset($data['accounts']) && count($data['accounts']) >1 ){
                                                            echo '<option value="all" '.(getVar('account_id') == 'all' ? 'selected' : "").'>All</option>';
                                                        }
                                                ?>
                                                <?php echo tool_dropdown_option($data['accounts'], (getVar('account_id') ? getVar('account_id') : ''), 'full_name'); ?>
                                            </select>
                                        </div>
                                    <?php } ?>
                                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                        <input name="pagination_keyword" type="text" class="form-control pd-x-10 pagination" data-parameter="keyword" placeholder="Search..." value="<?php echo getVar('keyword'); ?>">
                                    </div>
                                </div>
                                <div class="table-responsive bd rounded">
                                    <table class="table table-striped table-bordered table-hover mg-b-0">
                                        <thead class="thead-colored thead-dark">
                                            <tr>
                                                <th class="wd-20p">COMPANY NAME</th>
                                                <th class="wd-20p">CONTACT NO</th>
                                                <th class="wd-20p">CREATED BY</th>
                                                <th class="wd-20p">CREATED WHEN</th>
                                                <th class="wd-5p tx-center">ACTION</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if(isset($data['records']) && !empty($data['records'])){
                                                    foreach($data['records'] as $key => $value){
                                                        $action = '-';

                                                        if($value['created_by'] == ACCOUNT_ID || ACCOUNT_TYPE_ID == 1 || in_array(ACCOUNT_LEVEL_ID,$data['default_level_leader'])){
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
                                                                                <a href="#" class="nav-link edit-encode" data-action="edit" data-company_encode="'.idEncrypt(htmlDecode($value['id'])).'" "title="Edit Record" title="Update Lead"><i class="fa fa-pencil-square-o"></i> Update</a>
                                                                                <a href="#" class="nav-link delete-encode" data-action="delete" data-company_encode="'.idEncrypt(htmlDecode($value['id'])).'" "title="Delete" title="Remove Lead"><i class="fa fa-trash"></i> Delete</a>
                                                                            </nav>
                                                                        </div>
                                                                    </div>
                                                            ';
                                                        }
                                                        echo '
                                                            <tr id="'.$value['id'].'">
                                                                <td>'.htmlDecode($value['company_name']).'</td>
                                                                <td>'.htmlDecode($value['contact_no']).'</td>
                                                                <td>'.htmlDecode($value['uploader_name']).'</td>
                                                                <td>'.dateDisplaySystem($value['created_when']).'</td>
                                                                <td class="tx-center"> 
                                                                    '.$action.'
                                                                </td>
                                                            </tr>
                                                        ';
                                                    }
                                                }else{
                                                    echo '<tr><td colspan="5" class="tx-center">No record found</td></tr>';
                                                }
                                            ?> 
                                        </tbody>
                                    </table>
                                </div>
                                <?php if(isset($data['records']) && is_array($data['records'])){ ?>
                                    <div class="row mg-t-40">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 lh-22">
                                            <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                            <ul class="pagination mg-0 float-right">
                                                <?php echo tool_pagination(getVar('page'), $data['total_page'], '/company/encode/', 'page', true); ?>
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

    <div id="modal-encode" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center modal-lg" role="document">
            <div class="modal-content bd-0">
                <div class="modal-body pd-25">

                </div>
            </div>
        </div>
    </div>

    <div id="modal-import-bulk" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center modal-xx" role="document">
            <div class="modal-content bd-0">
                <div class="modal-body pd-25">

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        //DATATABLE FILTER
        $(document).ready(function(){
            $('.pagination').bind('blur change',function(e){
                e.preventDefault();

                var account_type_id      = "<?php echo ACCOUNT_TYPE_ID; ?>";
                var account_type_encoder = "<?php echo $data['account_type_encoder']; ?>";
                
                var link                 = "/<?php echo getVar('controller').'/'.getVar('view'); ?>/";
                var limit                = $('select[name=pagination_limit]').find(":selected").val();
                var account_id           = $('select[name=pagination_account_id]').find(":selected").val();
                var keyword              = encodeURIComponent($('input[name=pagination_keyword]').val());

                if(account_type_id != account_type_encoder){
                    var parameter        = '?page=1&limit='+limit+'&keyword='+keyword+'&account_id='+account_id;
                }else{
                    var parameter        = '?page=1&limit='+limit+'&keyword='+keyword;
                }

                window.location.replace(link+parameter);
            });
        });
    </script>

    <script type="text/javascript">
        //IMPORT
        $(document).ready(function(){
            $(document).on('click', '.import', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                
                var display     = $('#modal-import .modal-body');
                var redirect    = "<?php echo getCurrentUrl(); ?>";

                $.ajax({
                    url: '/company/import-bulk-encode/',
                    type: 'GET',
                    data: {redirect:redirect},
                    beforeSend: function(){
                        display.html('');
                    },
                    success: function(data){
                        $(data).appendTo(display);

                        $('#modal-import').modal('show');
                    },
                    error: function(xhr, desc, err){ 
                        console.warn(xhr.responseText);
                    }
                });
            });
        });
    </script>    

    <script type="text/javascript">
        //CONFIRM CONVERT
        $(document).ready(function(){
            $('input[name=confirm]').prop('checked', false);
            $('input[name=confirm]').click(function () {
                if($(this).is(":checked")){
                    $(this).addClass("selected");
                    $('button[name=submit]').removeAttr("disabled");
                }else{
                    $(this).removeClass("selected");
                    $('button[name=submit]').attr('disabled', true);
                }
            });
        });
    </script>

    <script type="text/javascript">
        //MANAGE
        $(document).ready(function(){
            $(document).on('click', '.add-encode', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                
                var display = $('#modal-encode .modal-body');

                $.ajax({
                    url: '/company/import-encode/',
                    type: 'POST',
                    beforeSend: function(){
                        display.html('');
                    },
                    success: function(data){
                        $(data).appendTo(display);

                        $('#modal-encode').modal('show');
                    },
                    error: function(xhr, desc, err){ 
                        console.warn(xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.edit-encode', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                
                var id      = $(this).data('company_encode');
                var display = $('#modal-encode .modal-body');

                $.ajax({
                    url: '/company/import-encode/',
                    type: 'POST',
                    data: {id:id},
                    beforeSend: function(){
                        display.html('');
                    },
                    success: function(data){
                        $(data).appendTo(display);

                        $('#modal-encode').modal('show');
                    },
                    error: function(xhr, desc, err){ 
                        console.warn(xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.delete-encode', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                
                var id      = $(this).data('company_encode');
                var action  = $(this).data('action');
                var display = $('#modal-encode .modal-body');

                if(confirm("Are you sure you want to delete this record?")){
                    $.ajax({
                        url: '/company/manage-encode-json/',
                        type: 'POST',
                        data: {id:id, action:action},
                        success: function(data){
                            alert(data.message);
                            window.location = document.URL;
                        },
                        error: function(xhr, desc, err){ 
                            console.warn(xhr.responseText);
                        }
                    });
                }
            });
        });
    </script> 

    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click','.tab-link',function(e){
                $('select[name=pagination_limit]').val(10);
                $('.pagination').trigger('change');
            });
        });
    </script>

    <script type="text/javascript">
        //IMPORT
        $(document).ready(function(){
            $(document).on('click', '.import-bulk', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                
                var display     = $('#modal-import-bulk .modal-body');
                var redirect    = "<?php echo getCurrentUrl(); ?>";

                $.ajax({
                    url: '/company/import-bulk/',
                    type: 'GET',
                    data: {redirect:redirect},
                    beforeSend: function(){
                        display.html('');
                    },
                    success: function(data){
                        $(data).appendTo(display);

                        $('#modal-import-bulk').modal('show');
                    },
                    error: function(xhr, desc, err){ 
                        console.warn(xhr.responseText);
                    }
                });
            });
        });
    </script>  