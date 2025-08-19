<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">SOA <span class="text-primary">[ Letters ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a href="/finance/soa-letter-manage" class="btn btn-primary text-white">
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
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Aging</th>
                                        <th>Letter</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php
                                        if(isset($data['letter']) && is_array($data['letter'])){
                                            foreach($data['letter'] as $key => $value){
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
                                                            '.htmlDecode($value['created_name']).'<br>
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
                                </tbody>
                            </table>
                        </div>

                        <?php if(isset($data['letter']) && is_array($data['letter'])){ ?>
                            <div class="row justify-content-between">
                                <div class="col-md-auto me-auto mt-8">
                                    <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                </div>

                                <div class="col-md-auto ms-auto mt-5">
                                    <ul class="pagination">
                                        <?php echo tool_pagination(getVar('page'), $data['total_page'], '/finance/soa-letter/', 'page', true); ?>
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