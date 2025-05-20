    <div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">Master</a>
                <a class="breadcrumb-item" href="/">Account Type</a>
                <span class="breadcrumb-item active">Records</span>
            </nav>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-cogs"></i>
            <div>
                <h4>Account Type <b class="tx-primary">[ Records ]</b></h4>
                <p class="mg-b-0">System configuration</p>
                <div class="pagetitle-button">
                    <button type="button" class="btn btn-info add" data-toggle="modal" data-target="#modal">
                        <i class="fa fa-plus-square fa-lg"></i> <small>ADD RECORD</small>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php flash(promptMessage('message')); ?>
        </div>
        <div class="br-pagebody">
            <div class="br-section-wrapper">
                <div class="table-wrapper">
                    <table class="table table-bordered display table_default">
                        <thead class="thead-colored thead-dark">
                            <tr>
                                <th class="wd-90p">NAME</th>
                                <th class="wd-10p"><center>ACTION</center></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(is_array($data['record'])){
                                    foreach($data['record'] as $key => $value){
                                        echo '<tr id="'.$value['id'].'">
                                                <td>'.htmlDecode($value['name']).'</td>
                                                <td>
                                                    <center>
                                                        <a id="'.htmlDecode($value['id']).'" class="btn btn-sm btn-list btn-warning edit" data-action="edit" title="Update Record"><i class="fa fa-pencil-square-o"></i></a>
                                                        <a id="'.htmlDecode($value['id']).'" class="btn btn-sm btn-list btn-danger delete" data-action="delete" data-title="'.htmlDecode($value['name']).'" title="Delete Record"><i class="fa fa-trash"></i></a>
                                                    </center>
                                                </td>
                                            </tr>';
                                    }
                                }
                            ?> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal fade">
        <div class="modal-dialog modal-dialog-vertical-center" role="document">
            <form id="form">
                <div class="modal-content bd-0">
                    <div class="modal-header pd-y-20 pd-x-25">
                        <h6 class="tx-14 mg-b-0 tx-uppercase tx-primary tx-bold modal-title"><span></span> Record</h6>
                        <button type="button" class="close cursor-pointer" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body pd-25">
                        <div class="row">
                            <label class="col-sm-4 form-control-label">Name: <span class="tx-danger">*</span></label>
                            <div class="col-sm-8 mg-t-10 mg-sm-t-0">
                                <input name="name" type="text" class="form-control" placeholder="">
                            </div>
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

    <script src="/public/lib/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/public/lib/datatables.net-dt/js/dataTables.dataTables.min.js"></script>
    <script src="/public/lib/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/public/lib/datatables.net-responsive-dt/js/responsive.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function(){
            'use strict';
            $('.table_default').DataTable({
                autoWidth: false,
                responsive: {
                    breakpoints: [
                        { name: 'desktop',  width: Infinity },
                        { name: 'tablet-l', width: 1024 },
                        { name: 'tablet-p', width: 768 },
                        { name: 'mobile-l', width: 480 },
                        { name: 'mobile-p', width: 320 }
                    ]
                },
                bSort: false,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ items/page',
                }
            });  

            $('.dataTables_length select').select2({ minimumResultsForSearch: Infinity });           
        });
    </script>

    <script type="text/javascript">
        //ADD
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
                    url: '/master/accountType_json/',
                    type: 'POST',
                    data: $('#form').serialize(),
                    beforeSend: function(){
                        promptAjaxLoading('form');
                    },
                    success: function(data){
                        promptAjaxSuccess('modal', data.message, 'reload');
                        console.log(data);
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
               });
            }
        });

        //ADD
        $(document).on('click', '.add', function(e){
            $('#modal .modal-title span').html('Add New');
            $('#modal input').val('');
        });

        //EDIT
        $(document).ready(function(){
            $(document).on('click', '.edit', function(e){
                e.preventDefault();
                var id      = $(this).attr('id');
                var action  = $(this).data('action');

                $.ajax({
                    url: '/master/accountType_json/',
                    type: 'POST',
                    data: {id:id, action:action},
                    success: function(data){
                        $('input[name=id]').val(data.id);
                        $('input[name=name]').val(data.name);

                        $('#modal .modal-title span').html('Edit');
                        $('#modal').modal('show');
                        
                        console.log(data);
                    },
                    error: function(xhr, desc, err){ 
                        //console.log(xhr);
                        console.warn(xhr.responseText);
                    }
               });
            });
        });

        //DELETE
        $(document).on('click', '.delete', function(e){
            e.preventDefault();
            var id      = $(this).attr('id');
            var action  = $(this).data('action');
            var title   = $(this).data('title');

            var check = confirm("Are you sure you want to delete?\n\n"+title);
            if(check == true){
                $.ajax({
                    url: '/master/accountType_json/',
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
    </script>