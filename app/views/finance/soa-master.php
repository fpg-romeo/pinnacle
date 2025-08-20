<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">Masterlist</h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a class="btn btn-primary text-white showModal" action="add" data-bs-toggle="modal" data-bs-target="#masterlistModal">
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
                    <div class="card-body pb-2">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>SOURCE NAME</th>
                                        <th>HANDLERS</th>
                                        <th>TEAM LEADER</th>
                                        <th></th>
                                        <th>STATUS</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php
                                        if(is_array($data['masterlists'])){
                                            foreach($data['masterlists'] as $masterlist){
                                    ?>
                                                <tr>
                                                    <td><?=$masterlist['source_name']?></td>
                                                    <td><?=$masterlist['handler']?></td>
                                                    <td><?=$masterlist['team_leader']?></td>
                                                    <td></td>
                                                    <td><?=$masterlist['is_active'] == 1 ? "Active" : "Inactive"?></td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn px-3 py-1 dropdown-toggle btn-outline-secondary" data-bs-toggle="dropdown">
                                                                <i class="icon-base ti tabler-settings"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item showModal" action="edit" data-bs-toggle="modal" data-bs-target="#masterlistModal" data-id="<?=$masterlist['id']?>"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>
                                                                <a class="dropdown-item showModal" action="show" data-bs-toggle="modal" data-bs-target="#masterlistModal" data-id="<?=$masterlist['id']?>"><i class="icon-base ti tabler-eye me-1"></i> Show</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        }else{
                                            echo '<tr><td colspan="6" class="text-center">No record found</td></tr>';
                                        }
                                    ?> 
                                </tbody>
                            </table>
                        </div>

                        <?php if(is_array($data['masterlists'])){ ?>
                            <div class="row justify-content-between">
                                <div class="col-md-auto me-auto mt-8">
                                    <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                </div>

                                <div class="col-md-auto ms-auto mt-5">
                                    <ul class="pagination">
                                        <?php echo tool_pagination(getVar('page'), $data['total_page'], '/finance/soa-master/', 'page', true); ?>
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

<div class="modal fade" id="masterlistModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <form method="post" id="masterlistForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel3">Masterlist</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="intermediary_id" class="form-label">Source Name <span>*</span></label>
              <select id="intermediary_id" name="intermediary_id" class="select2 form-select" required>
                <?= tool_dropdown_option($data['intermediaries'], '', 'source_name'); ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="branch" class="form-label">Branches <span>(Optional)</span></label>
              <div class="select2-primary">
                <select id="branch" name="branch[]" class="select2 form-select" multiple>
                  <?= tool_dropdown_option($data['branches'], '', 'name'); ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="segment" class="form-label">Segments <span>(Optional)</span></label>
              <select id="segment" name="segment[]" class="select2 form-select" multiple>
                <?= tool_dropdown_option($data['segments'], '', 'name'); ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="insured_name" class="form-label">Insured Name <span>(Optional)</span></label>
              <input type="text" id="insured_name" class="form-control" name="insured_name">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="sales_channel" class="form-label">Sales Channel <span>(Optional)</span></label>
              <select id="sales_channel" name="sales_channel[]" class="select2 form-select" multiple>
                <?= tool_dropdown_option($data['sales_channels'], '', 'name'); ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="topro" class="form-label">TOPRO <span>(Optional)</span></label>
              <select id="topro" name="topro[]" class="select2 form-select" multiple>
                <option value=""></option>
                <?= tool_dropdown_option($data['topros'], '', 'code'); ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-12 mb-5">
              <label for="class_of_business" class="form-label">COB Description <span>(Optional)</span></label>
              <select id="class_of_business" name="class_of_business[]" class="select2 form-select" multiple>
                <?= tool_dropdown_option($data['cobs'], '', 'name'); ?>
              </select>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 mb-5">
              <label for="account_name" class="form-label">Account Name <span>(Optional)</span></label>
              <input type="text" class="form-control" id="account_name" name="account_name">
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="handler_id" class="form-label">Handler <span>*</span></label>
              <select id="handler_id" name="handler_id" class="select2 form-select" required>
                <option value=""></option>
                <?php
                    foreach($data['handlers'] as $handler){
                ?>
                        <option value="<?=$handler['id']?>"><?=$handler['first_name'] . " " . $handler['last_name']; ?></option>
                <?php
                    }
                ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="team_leader_id" class="form-label">Team Leader <span>*</span></label>
              <select id="team_leader_id" name="team_leader_id" class="select2 form-select" required>
                <option value=""></option>
                <?php
                    foreach($data['team_leaders'] as $team_leader){
                ?>
                        <option value="<?=$team_leader['id']?>"><?=$team_leader['first_name'] . " " . $team_leader['last_name']; ?></option>
                <?php
                    }
                ?>
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="or_recipients" class="form-label">OR Recipients <span>(Optional)</span></label>
              <input id="or_recipients" class="form-control" name="or_recipients[]" value="" />
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 mb-5">
              <label for="soa_recipients" class="form-label">SOA Recipients <span>*</span></label>
              <input id="soa_recipients" class="form-control" name="soa_recipients[]" value="" />
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-12 mb-5">
              <label for="intermediary_code" class="form-label">Intermediary Code <span>*</span></label>
              <input type="text" class="form-control" value="" id="intermediary_code" name="intermediary_code" required>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 mb-5">
              <label for="is_active" class="form-label">Status</label>
              <select id="is_active" name="is_active" class="select2 form-select">
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

<script>
    $('#masterlistModal .select2').select2({
        dropdownParent: $('#masterlistModal')
    });

    const tagifyBasicEl = document.querySelector("#or_recipients");
    const TagifyBasicOr = new Tagify(tagifyBasicEl);
    
    const tagifyBasic = document.querySelector("#soa_recipients");
    const TagifyBasicSoa = new Tagify(tagifyBasic);
</script>

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


    $('.showModal').click(function(e){
      
      e.preventDefault();
      
      $('#masterlistModal').find('input').prop('readonly', false);
      $('#masterlistModal').find('select').prop('disabled', false);
      var action = $(this).attr('action');
      $('[name="action"]').val(action);
      $('.submit').css('display', 'block');

      if(action != 'add'){
        $.ajax({
          url: '/finance/soaMaster_json/',
          method: 'POST',
          data:{
            id: $(this).data('id')
          },
          success: function(data){
              $('[name="id"]').val(data.id);
              $('#intermediary_id').val(data.intermediary_id).trigger("change");
              $('#handler_id').val(data.handler_id).trigger("change");
              $('#team_leader_id').val(data.team_leader_id).trigger("change");
              $('#branch').val(data.branch.branch_id).trigger("change");
              $('#topro').val(data.topro.topro_id).trigger("change");
              $('#sales_channel').val(data.sales_channel.sales_channel_id).trigger("change");
              $('#segment').val(data.segment.segment_id).trigger("change");
              $('#class_of_business').val(data.class_of_business.class_of_business_id).trigger("change");
              $('#or_recipients').val(data.official_receipt.email);
              $('#soa_recipients').val(data.soa_recipients.email);
              $('#account_name').val(data.account_name);
              $('#email').val(data.email);
              $('#intermediary_code').val(data.intermediary_code);
              $('#is_active').val(data.is_active).trigger("change");
          }
        });

        if(action == "show"){
          $('#masterlistModal').find('input').prop('readonly', true);
          $('#masterlistModal').find('select').prop('disabled', true);
          $('.submit').css('display', 'none');
        }
      }
  });

</script>
