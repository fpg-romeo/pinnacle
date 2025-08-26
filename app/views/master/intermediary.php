<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-between">
      <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
        <h4 class="lh-lg mb-0 fw-bolder">Intermediaries <span class="text-primary">[ List ]</span></h4>
      </div>
      <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
        <button type="button" class="btn btn-primary text-white" action="sync" id="syncBtn">
          <i class="icon-base ti tabler-plus me-2"></i>
          <span class="align-middle">Sync</span>
        </button>
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
              <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                <select name="pagination_status" class="form-control select pagination" data-placeholder="Status">
                  <?php echo tool_dropdown_option($data['account_status'], (getVar('status') ? getVar('status') : 1), 'name'); ?>
                </select>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive text-nowrap">
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Source Name</th>
                    <th>Status</th>
                    <th>Sync Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                  <?php if (!empty($data['record']) && is_array($data['record'])) { ?>
                    <?php
                    foreach ($data['record'] as $intermediary) {
                    ?>
                      <tr>

                        <td><?= $intermediary['source_name'] ?></td>
                        <td><?= $intermediary['is_active'] ? 'Active' : 'Inactive' ?></td>
                        <td><?= $intermediary['created_at'] ?></td>
                        <td>
                          <div class="dropdown">
                            <button type="button" class="btn px-3 py-1 dropdown-toggle btn-outline-secondary" data-bs-toggle="dropdown">
                              <i class="icon-base ti tabler-settings"></i>
                            </button>
                            <div class="dropdown-menu">
                              <a class="dropdown-item showModal" id="editItem" action="edit" data-bs-toggle="modal" data-bs-target="#intermediaryModal" data-id="<?= $intermediary['id'] ?>"><i class="icon-base ti tabler-pencil me-1"></i> Edit</a>
                            </div>
                          </div>
                        </td>

                      </tr>
                    <?php } ?>
                  <?php } else { ?>
                    <tr>
                      <td colspan="4" class="text-center">No intermidiaries found</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

            <?php if (is_array($data['record'])) { ?>
              <div class="row justify-content-between">
                <div class="col-md-auto me-auto mt-8">
                  <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                </div>

                <div class="col-md-auto ms-auto mt-5">
                  <ul class="pagination">
                    <?php echo tool_pagination(getVar('page'), $data['total_page'], '/master/intermediary/', 'page', true); ?>
                  </ul>
                </div>

              </div>
            <?php } ?>

            <div class="row justify-content-between">
              <!-- <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
                <div class="dt-info" aria-live="polite" id="DataTables_Table_0_info" role="status">
                  Showing 1 to 10 of 100 entries
                </div>
              </div>
              <div class="d-md-flex justify-content-between align-items-center dt-layout-end col-md-auto ms-auto mt-5">
                <div class="dt-paging">
                  <nav aria-label="pagination">
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
                  </nav>
                </div>
              </div>
            </div> -->

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="intermediaryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <form method="post" id="intermediaryForm">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel3">Intermediary</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div>
                <input type="hidden" name="idItem" id="idItem">
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12 mb-4">
                <label for="sourcename" class="form-label">Source Name</label>
                <input type="text" id="sourcename" name="sourcename" class="form-control">
              </div>
              <div class="col-lg-12 col-md-6 col-xs-12 mb-4">
                <label for="address" class="form-label">Address</label>
                <input type="text" id="address" name="address" class="form-control">
              </div>
              <div class="col-lg-6 col-md-6 col-xs-12 mb-4">
                <label for="category" class="form-label">Category</label>
                <select id="category" class="form-control" name="category" data-style="btn-default">
                  <option value="1">Agent</option>
                  <option value="0">Broker</option>
                </select>
              </div>
            </div>
            <div class="row g-4">
              <div class="col-lg-4 col-md-4 col-xs-12 mb-4">
                <label for="is_active" class="form-label">Status</label>
                <select id="is_active" class="form-control" name="is_active" data-style="btn-default">
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
            <button type="button" class="btn btn-primary" action="save" id="saveItem">Save Changes</button>
          </div>
        </div>
      </form>
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
        var status = $('select[name=pagination_status]').find(":selected").val();
        var parameter = '?page=1&limit=' + limit + '&keyword=' + keyword + '&status=' + status;

        window.location.replace(link + parameter);
      });
    });
  </script>

  <script>
    $(document).ready(function() {
      $('#syncBtn').on('click', function(e) {
        e.preventDefault();
        console.log('Sync button clicked');

        $.ajax({
          url: '/master/intermediary_json/',
          type: 'POST',
          dataType: 'json',
          data: {
            action: 'sync'
          },
          success: function(response) {
            console.log(response);
            alert(response.message || 'Sync successful!');
            location.reload();
          },
          error: function(xhr, status, error) {
            alert('Error: ' + error + ' - ' + xhr.responseText);
          }
        });
      });

      $('.showModal').on('click', function() {
        var id = $(this).data('id');
        console.log('Edit button clicked for ID:', id);

        $.ajax({
          url: '/master/intermediary_json/',
          type: 'POST',
          dataType: 'json',
          data: {
            action: 'edit',
            id: id
          },
          success: function(response) {
            $('#intermediaryForm input[name="idItem"]').val(response.id)
            $('#intermediaryForm input[name="sourcename"]').val(response.sourcename)
            $('#intermediaryForm input[name="address"]').val(response.address)
            $('#intermediaryForm select[name="category"]').val(response.category)
            $('#intermediaryForm select[name="is_active"]').val(response.is_active)

          },
          error: function(xhr, status, error) {
            alert('Error: ' + error + ' - ' + xhr.responseText);
          }
        });
      });

      $('#saveItem').on('click', function() {
        var action = $(this).attr('action');
        var id = $('#intermediaryForm input[name="idItem"]').val();
        var sourcename = $('#intermediaryForm input[name="sourcename"]').val();
        var address = $('#intermediaryForm input[name="address"]').val();
        var category = $('#intermediaryForm select[name="category"]').val();
        var is_active = $('#intermediaryForm select[name="is_active"]').val();


        $.ajax({
          url: '/master/intermediary_json/',
          type: 'POST',
          dataType: 'json',
          data: {
            action: action,
            id: id,
            sourcename: sourcename,
            address: address,
            category: category,
            is_active: is_active
          },
          success: function(response) {
            alert(response.message || 'Changes saved successfully!');
            location.reload();
          },
          error: function(xhr, status, error) {
            console.error('Error:', error, xhr.responseText);
            alert('Error: ' + error + ' - ' + xhr.responseText);
          }
        });
      });

    });
  </script>