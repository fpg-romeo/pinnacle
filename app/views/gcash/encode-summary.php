    <div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">Claims</a>
                <a class="breadcrumb-item" href="/">GCash</a>
                <span class="breadcrumb-item active">Summary</span>
            </nav>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-wpforms"></i>
            <div>
                <h4>GCash <b class="tx-primary">[ Summary ]</b></h4>
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
                <div class="row mg-b-20">
                    <div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
                        <select name="pagination_limit" class="form-control select pagination" data-parameter="limit" data-placeholder="Limit">
                            <?php echo tool_dropdown_value(value_pagination_limit(), (getVar('limit') ? getVar('limit') : 10)); ?>
                        </select>
                    </div>
                    
                        <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                            <select name="pagination_account_id" class="form-control select pagination" data-placeholder="Filter By Account">
                                <?php 
                                        if(isset($data['accounts']) && count($data['accounts']) >1 ){
                                            echo '<option value="all" '.(getVar('account_id') == 'all' ? 'selected' : "").'>All</option>';
                                        }
                                ?>
                                <?php echo tool_dropdown_option($data['accounts'], (getVar('account_id') ? getVar('account_id') : ''), 'full_name'); ?>
                            </select>
                        </div>
                
                </div>
                <div class="table-responsive bd rounded">
                    <table class="table table-striped table-bordered table-hover mg-b-0">
                        <thead class="thead-colored thead-dark">
                            <tr>
                                <th class="wd-10p tx-center">DATE</th>
                                <th class="wd-10p tx-center">NO. OF <br>UPLOAD</th>
                                <th class="wd-10p tx-center">NO. OF <br>DUPLICATE</th>
                                <th class="wd-10p tx-center">NO. OF <br>FAILED</th>
                                <th class="wd-10p tx-center">NO. OF <br>DELETED</th>
                                <th class="wd-10p tx-center">NO. OF <br>MANUAL ENTRY</th>
                                <th class="wd-5p tx-center">TOTAL</th>
                                <th class="wd-10p">MANAGED BY</th>
                                <th class="wd-25p">UPLOADED FILE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($data['summary']) && !empty($data['summary'])){
                                    foreach($data['summary'] as $key_summary => $value_summary){
                                        echo '
                                            <tr >
                                                <td class="tx-center">'.dateDisplaySystem($value_summary['created_when']).'</td>
                                                <td class="tx-center">'.htmlDecode($value_summary['success']).'</td>
                                                <td class="tx-center">'.htmlDecode($value_summary['duplicate']).'</td>
                                                <td class="tx-center">'.htmlDecode($value_summary['failed']).'</td>
                                                <td class="tx-center">'.htmlDecode($value_summary['deleted']).'</td>
                                                <td class="tx-center">'.htmlDecode($value_summary['manual_entry']).'</td>
                                                <td class="tx-center">'.($value_summary['success']+$value_summary['duplicate']+$value_summary['failed']+$value_summary['deleted']+$value_summary['manual_entry']).'</td>
                                                <td>'.htmlDecode($value_summary['uploader_name']).'</td>
                                                <td>'.(!empty($value_summary['file']) ? '<span class="tx-14 valign-top"><i class="icon ion-android-attach"></i><small> <a href="/file/company/'.htmlDecode($value_summary['file']).'" target="_blank">'.htmlDecode($value_summary['file_name']).'</a></small></span>' : '').'</td>
                                            </tr>
                                        ';
                                    }
                                }else{
                                    echo '<tr><td colspan="9" class="tx-center">No record found</td></tr>';
                                }
                            ?> 
                        </tbody>
                    </table>
                </div> 
                <?php if(isset($data['summary']) && is_array($data['summary'])){ ?>

                    <div class="row mg-t-40">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 lh-22">
                            <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <ul class="pagination mg-0 float-right">
                                <?php echo tool_pagination(getVar('page'), $data['total_page'], '/company/encode-summary/', 'page', true); ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                         
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

    <script type="text/javascript">
        //DATATABLE FILTER
        $(document).ready(function(){
            $('.pagination').bind('blur change',function(e){
                e.preventDefault();

                var link       = "/<?php echo getVar('controller').'/'.getVar('view'); ?>/";
                var limit      = $('select[name=pagination_limit]').find(":selected").val();
                var account_id = $('select[name=pagination_account_id]').find(":selected").val();

                var parameter  = '?page=1&limit='+limit+'&account_id='+account_id;

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