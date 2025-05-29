    <div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">Human Resources</a>
                <a class="breadcrumb-item" href="/">Employee</a>
                <span class="breadcrumb-item active">Records</span>
            </nav>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-users"></i>
            <div>
                <h4>Employee <b class="tx-primary">[ Records ]</b></h4>
                <p class="mg-b-0">Accounts Information</p>
                <div class="pagetitle-button">
                    <a href="#" class="btn btn-info mg-r-10" data-toggle="modal" data-target="#modal-relogin">
                        <i class="fa fa-sign-out fa-lg"></i> <small>RELOGIN</small>
                    </a>
                    <a href="/account/manage/" class="btn btn-info">
                        <i class="fa fa-plus-circle fa-lg"></i> <small>ADD RECORD</small>
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
                    <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
                        <select name="pagination_limit" class="form-control select pagination" data-parameter="limit" data-placeholder="Limit" autocomplete="off">
                            <?php echo tool_dropdown_value(value_pagination_limit(), (getVar('limit') ? getVar('limit') : 10)); ?>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <input name="pagination_keyword" type="text" class="form-control pd-x-10 pagination" data-parameter="keyword" placeholder="Search..." value="<?php echo getVar('keyword'); ?>">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-3">
                        <select name="pagination_department" class="form-control select pagination" data-placeholder="Department">
                            <option value="all">All</option>
                            <?php echo tool_dropdown_option($data['account_department'], getVar('department'), 'name'); ?>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                        <select name="pagination_status" class="form-control select pagination" data-placeholder="Status">
                        <?php echo tool_dropdown_option($data['account_status'], (getVar('status') ? getVar('status') : 1), 'name'); ?>
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                        <a href="/account/all/1" type="button" class="btn btn-info mg-l-2 reset">
                            <i class="fa fa-refresh fa-lg"></i> <small>RESET</small>
                        </a>
                    </div>
                </div>             
                <div class="table-wrapper">
                    <!--<table class="table table-bordered display ">-->
                    <table class="table table-striped table-bordered bd mg-0 table-responsive d-md-table">
                        <thead class="thead-colored thead-dark">
                            <tr>
                                <th class="wd-5p tx-center">PHOTO</th>
                                <th class="wd-20p">NAME</th>
                                <th class="wd-15p">ACTIVE DIRECTORY</th>
                                <th class="wd-20p">EMAIL / CONTACT NO.</th>
                                <th class="wd-20p">DEPARTMENT</th>
                                <th class="wd-10p">TYPE</th>
                                <th class="wd-5p tx-center">STATUS</th>
                                <th class="wd-5p tx-center"><center>ACTION</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(is_array($data['user'])){
                                    foreach($data['user'] as $key => $value){
                                        echo '
                                            <tr id="'.$value['id'].'">
                                                <td class="tx-center cursor-pointer view-image" data-photo="' . displayImage($value['user_photo'], 'account') . '" data-name="' . htmlDecode($value['first_name']) . ' ' . htmlDecode($value['last_name']) . '">
                                                    <img src="' . displayImage($value['user_photo'], 'account'). '" class="img-fluid" alt="User Photo">
                                                </td>
                                                <td>
                                                    '.htmlDecode($value['first_name']).' '.htmlDecode($value['last_name']).'
                                                    '.(!empty(htmlDecode($value['alias'])) ? '<br><i class="text-muted">'.htmlDecode($value['alias']).'</i>' : '').'
                                                </td>
                                                <td>'.htmlDecode($value['active_directory']).'</td>
                                                <td>
                                                    '.htmlDecode($value['email']).'
                                                    <br><small class="text-muted">'.htmlDecode($value['contact_no']).'</small>
                                                </td>
                                                <td>
                                                    '.htmlDecode($value['account_department_name']).'
                                                    '.(!empty($value['account_team_name']) ? '<br><small class="text-muted">'.htmlDecode($value['account_team_name']).'</small>' : '').'
                                                </td>
                                                <td>'.htmlDecode($value['account_type_name']).'</td>
                                                <td class="tx-center">'.htmlDecode($value['account_status_name']).'</td>
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
                                                                <a data-id="'.idEncrypt(htmlDecode($value['id'])).'"   class="nav-link view" data-action="view" title="View Record"><i class="fa fa-file-text-o"></i> Details</a>
                                                                <a href="/account/manage/'.idEncrypt(htmlDecode($value['id'])).'" class="nav-link" title="Update Record"><i class="fa fa-pencil-square-o"></i> Update</a>
                                                                <!--<a href="/account/assessment/'.idEncrypt(htmlDecode($value['id'])).'" class="nav-link" title="Grade User"><i class="fa fa-list-ol"></i> Assessment</a>-->
                                                                <a id="'.htmlDecode($value['id']).'" class="nav-link delete" data-action="delete" data-title="'.htmlDecode($value['first_name']).' '.htmlDecode($value['last_name']).'" title="Delete Record"><i class="fa fa-trash"></i> Delete</a>  
                                                            </nav>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        ';
                                    }
                                }else{
                                    echo '<tr><td colspan="8" class="tx-center">No record found</td></tr>';
                                }
                            ?> 
                            
                        </tbody>
                    </table>
                </div>
                <?php if(is_array($data['user'])){ ?>
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

    <div id="modal_view" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center modal-xl" role="document">
            <div class="modal-content bd-0">
                <div class="modal-body pd-25">

                </div>
            </div>
        </div>
    </div>

    <div id="modal-relogin" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center modal-md" role="document">
            <div class="modal-content bd-0">
                <form id="account-relogin" role="form" method="post">                    
                    <div class="modal-header pd-y-20 pd-x-25">
                        <h6 class="tx-14 mg-b-0 tx-uppercase tx-primary tx-bold modal-title"><span></span> User Accounts Relogin</h6>
                    </div>
                    <div class="modal-body pd-25 pd-y-40 text-center">
                        <p class="text-muted">Are you sure you want to relogin all accounts?</p>
                    </div>
                    <div class="modal-footer">
                        <center>
                            <button name="submit-relogin" type="submit" class="btn btn-primary btn-form"><small>SUBMIT</small></button>
                            <button class="btn btn-secondary btn-form modal-cancel" data-dismiss="modal"><small>CANCEL</small></button>
                        </center>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modal-view-image" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center modal-sm" role="document">
            <form id="form">
                <div class="modal-content bd-0">
                    <div class="modal-header pd-y-20 pd-x-25">
                        <h6 class="tx-14 mg-b-0 tx-uppercase tx-primary tx-bold modal-title"><span></span> </h6>
                        <button type="button" class="close cursor-pointer" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body pd-25">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="/public/lib/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/public/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
    <script src="/public/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/public/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
    <script type="text/javascript">
        //DATATABLE FILTER
        $(document).ready(function(){
            $('.pagination').bind('blur change',function(e){
                e.preventDefault();

                var link        = "/<?php echo getVar('controller').'/'.getVar('view'); ?>/";
                var limit       = $('select[name=pagination_limit]').find(":selected").val();
                var status      = $('select[name=pagination_status]').find(":selected").val();
                var keyword     = encodeURIComponent($('input[name=pagination_keyword]').val());
                var department  = $('select[name=pagination_department]').find(":selected").val();

                var parameter   = '?page=1&limit='+limit+'&keyword='+keyword+'&department='+department+'&status='+status;
                
                window.location.replace(link+parameter);
            });
        });
    </script>

    <script type="text/javascript">
        // $(function(){
        //     'use strict';
        //     $('.table_default').DataTable({
        //         autoWidth: false,
        //         responsive: {
        //             breakpoints: [
        //                 { name: 'desktop',  width: Infinity },
        //                 { name: 'tablet-l', width: 1024 },
        //                 { name: 'tablet-p', width: 768 },
        //                 { name: 'mobile-l', width: 480 },
        //                 { name: 'mobile-p', width: 320 }
        //             ]
        //         },
        //         bSort: false,
        //         language: {
        //             searchPlaceholder: 'Search...',
        //             sSearch: '',
        //             lengthMenu: '_MENU_ items/page',
        //         }
        //     });  

        //     $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });           
        // });
    </script>

    <script type="text/javascript">
        //DELETE
        $(document).on('click', '.delete', function(e){
            e.preventDefault();
            var id      = $(this).attr('id');
            var action  = $(this).data('action');
            var title   = $(this).data('title');

            var check = confirm("Are you sure you want to delete?\n\n"+title);
            if(check == true){
                $.ajax({
                    url: '/account/delete-json/',
                    type: 'POST',
                    data: {id:id, action:action},
                    success: function(data){
                        console.log(data);
                        alert(data.message);
                        //location.reload();
                        window.location = document.URL;
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
                var id = $(this).data('id');
                var display = $('#modal_view .modal-body');
                // console.log(pipeline_id);
                $.ajax({
                    url: '/account/import-view/',
                    type: 'GET',
                    data: {
                        account_id:id
                    },
                    beforeSend: function(){
                        display.html('');
                    },
                    success: function(data){
                        // console.log('success');
                        $(data).appendTo(display);

                        $('#modal_view').modal('show');
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
                });
            });
        });

        // $("#modal_view").on("hidden.bs.modal", function(){
        //     $(".modal-body").html("");
        //     console.log('closed');
        // });

        // $(document).on('click', '.view', function(e){
        //     e.preventDefault();
        //     var id      = $(this).attr('id');
        //     var action  = $(this).data('action');

        //     // var business_id = $(this).data('business')
        //     //         pipeline_id = $(this).data('pipeline')
        //     //         column = $(this).data('column');



        //     console.log( id );

        //     $('#modal_view').modal('show');

        //     $.ajax({
        //         url: '/account/view-json/',
        //         type: 'POST',
        //         data: {id:id, action:action},
        //         dataType: 'json',
        //         success: function(data){
        //             $('#account_type_name').html(data['account_type_name']);
        //             $('#active_directory').html(data['email']);
        //             $('#name').html(data['full_name']);
        //             $('#email').html(data['email']);
        //             $('#account_unit_name').html(data['account_unit_team']);
        //             $('#role').html(data['account_role_name']);
        //             $('#account_status_name').html(data['account_status_name']);
        //         },
        //         error: function(xhr, desc, err){ 
        //             //console.log(xhr);
        //             console.warn(xhr.responseText);
        //         }
        //     });
        // });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(document).on('click', '.view-image', function(e){
                e.preventDefault();
                e.stopImmediatePropagation();
                e.stopPropagation();
                var photo       = $(this).data('photo');
                var name        = $(this).data('name');
                var display     = $('#modal-view-image .modal-body');
                display.html('');
                if(photo == ''){
                    $('<img src="'+photo+'" class="img-fluid">').appendTo(display);
                }else{
                    $('<img src="'+photo+'" class="img-fluid">').appendTo(display);
                }
                $('#modal-view-image .modal-title span').html(name);
                $('#modal-view-image').modal('show');
            });
        });
    </script>
