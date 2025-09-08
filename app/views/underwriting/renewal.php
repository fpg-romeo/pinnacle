<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">Underwriting <span class="text-primary">[ Renewal ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <a class="btn btn-info text-white import" action="add" data-bs-toggle="modal" data-bs-target="#modal-import">
                    <i class="icon-base ti tabler-upload me-2"></i>
                    <span class="align-middle">Upload</span>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row">
                            <div class="mb-6 col-lg-6 col-xl-1 col-12 mb-0">
                                <select name="pagination_limit" class="form-select select2 pagination" data-parameter="limit" data-placeholder="Limit" autocomplete="off">
                                    <?php echo tool_dropdown_value(value_pagination_limit(), (getVar('limit') ? getVar('limit') : 10)); ?>
                                </select>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <select name="pagination_account_id" class="form-control select2 pagination" data-placeholder="Filter By Account">
                                    <?php
                                    if (isset($data['accounts']) && count($data['accounts']) > 1) {
                                        echo '<option value="all" ' . (getVar('account_id') == 'all' ? 'selected' : "") . '>All</option>';
                                    }
                                    ?>
                                    <?php echo tool_dropdown_option($data['accounts'], (getVar('account_id') ? getVar('account_id') : ''), 'full_name'); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pb-2">
                        <div class="nav-align-top nav-tabs-shadow">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a
                                        type="button"
                                        class="nav-link"
                                        href=""
                                        data-bs-toggle="tab"
                                        aria-selected="true">
                                        Summary
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a
                                        href="/underwriting/renewal/"
                                        type="button"
                                        class="nav-link active"
                                        aria-controls="navs-top-align-profile"
                                        aria-selected="false">
                                        All Records
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="table-responsive no-wrap">
                                    <table class="table table-striped table-bordered table-hover mg-b-0">
                                        <thead class="thead-colored thead-dark">
                                            <tr>
                                                <th class="wd-5p">BATCH NUMBER</th>
                                                <th class="wd-10p">POLICY NO</th>
                                                <th class="wd-15p">REMARKS</th>
                                                <th class="wd-5p tx-center">OCCUPANCY</th>
                                                <th class="wd-10p tx-center">INCEPTION DATE</th>
                                                <th class="wd-10p tx-center">EXPIRY DATE</th>
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
                                                                    <td>' . ucwords(htmlDecode($value['batch_id'])) . '</td>
                                                                    <td>' . htmlDecode($value['policy_no']) . '</td>
                                                                    <td>' . htmlDecode($value['remarks']) . '</td> 
                                                                    <td class="tx-center">' . htmlDecode($value['occupancy']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['inception_date']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['expiry_date']) . '</td>
                                                                    <td class="tx-center">' . htmlDecode($value['account_name']) . '</td>
                                                                    <td class="tx-center">' . dateDisplaySystem($value['created_when']) . '</td>
                                                                    <td class="tx-center"> 
                                                                        <div class="dropdown">
                                                                            <button type="button" class="btn px-3 py-1 dropdown-toggle btn-outline-secondary" data-bs-toggle="dropdown">
                                                                                <i class="icon-base ti tabler-settings"></i>
                                                                            </button>
                                                                            <div class="dropdown-menu">
                                                                                <a class="dropdown-item showModal" data-action="view" data-bs-toggle="modal" data-bs-target="#renewalModal" data-id="'.idEncrypt(htmlDecode($value['id'])).'"><i class="icon-base ti tabler-eye me-1"></i> Show</a>
                                                                                
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
                            </div>
                        </div>
                        <?php if(is_array($data['records'])){ ?>
                            <div class="row justify-content-between">
                                <div class="col-md-auto me-auto mt-8">
                                    <?php echo paginationCounter(getVar('page'), arrayKeyExist($data, 'total_page'), arrayKeyExist($data, 'total_record')); ?>
                                </div>

                                <div class="col-md-auto ms-auto mt-5">
                                    <ul class="pagination">
                                        <?php echo tool_pagination(getVar('page'), $data['total_page'], '/underwriting/renewal/', 'page', true); ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal-import" class="modal fade">
            <div class="modal-dialog modal-dialog-vertical-center modal-lg">
                <div class="modal-content bd-0">
                    <div class="modal-body pd-25">

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="renewalModal" tabindex="-1" aria-labelledby="viewPolicyLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewPolicyLabel">Renewal Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="policyForm">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Policy No</label>
                                <input type="text" class="form-control" id="policy_no" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Remarks</label>
                                <input type="text" class="form-control" id="remarks" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Occupancy</label>
                                <input type="text" class="form-control" id="occupancy" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tariff Code</label>
                                <input type="text" class="form-control" id="tariff_code" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Expiring Rate</label>
                                <input type="text" class="form-control" id="expiring_rate" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Renewal Rate</label>
                                <input type="text" class="form-control" id="renewal_rate" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Endorsement No</label>
                                <input type="text" class="form-control" id="endorsement_no" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Renewal No</label>
                                <input type="text" class="form-control" id="renewal_no" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">CI No</label>
                                <input type="text" class="form-control" id="ci_no" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Reference No</label>
                                <input type="text" class="form-control" id="reference_no" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Co-Insurance</label>
                                <input type="text" class="form-control" id="co_insurance" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Insured Name</label>
                                <input type="text" class="form-control" id="insured_name" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Contact Numbers</label>
                                <input type="text" class="form-control" id="contact_numbers" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Inception Date</label>
                                <input type="text" class="form-control" id="inception_date" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Expiry Date</label>
                                <input type="text" class="form-control" id="expiry_date" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Booking Date</label>
                                <input type="text" class="form-control" id="booking_date" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Branch</label>
                                <input type="text" class="form-control" id="branch" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Branch Name</label>
                                <input type="text" class="form-control" id="branch_name" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Channel</label>
                                <input type="text" class="form-control" id="channel" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Channel 2</label>
                                <input type="text" class="form-control" id="channel_2" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Channel 3</label>
                                <input type="text" class="form-control" id="channel_3" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">TOC</label>
                                <input type="text" class="form-control" id="toc" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">COB</label>
                                <input type="text" class="form-control" id="cob" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">FOB</label>
                                <input type="text" class="form-control" id="fob" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Policy Type</label>
                                <input type="text" class="form-control" id="policy_type" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">MO</label>
                                <input type="text" class="form-control" id="mo" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Segment</label>
                                <input type="text" class="form-control" id="segment" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Segment Desc</label>
                                <input type="text" class="form-control" id="segment_desc" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Our Share</label>
                                <input type="text" class="form-control" id="ourshare" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Gross</label>
                                <input type="text" class="form-control" id="gross" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pct Share</label>
                                <input type="text" class="form-control" id="pctshare" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Facultative</label>
                                <input type="text" class="form-control" id="facultative" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">FShare</label>
                                <input type="text" class="form-control" id="fshare" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Sum Insured</label>
                                <input type="text" class="form-control" id="total_sum_insured" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Basic Premium</label>
                                <input type="text" class="form-control" id="basic_premium" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">DST</label>
                                <input type="text" class="form-control" id="dst" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">VAT</label>
                                <input type="text" class="form-control" id="vat" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">FST</label>
                                <input type="text" class="form-control" id="fst" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">LGT</label>
                                <input type="text" class="form-control" id="lgt" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Premium</label>
                                <input type="text" class="form-control" id="total_premium" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Coverage</label>
                                <input type="text" class="form-control" id="coverage" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">BS Code</label>
                                <input type="text" class="form-control" id="bscode" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">BS Name</label>
                                <input type="text" class="form-control" id="bsname" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Fee</label>
                                <input type="text" class="form-control" id="fee" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Discount</label>
                                <input type="text" class="form-control" id="discount" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">No of Claim</label>
                                <input type="text" class="form-control" id="nofclaim" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">OS Claim</label>
                                <input type="text" class="form-control" id="os_claim" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Settled Claim</label>
                                <input type="text" class="form-control" id="settled_claim" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Premium Paid</label>
                                <input type="text" class="form-control" id="premiumpaid" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Loss Ratio</label>
                                <input type="text" class="form-control" id="loss_ratio" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Location of Risk</label>
                                <input type="text" class="form-control" id="location_of_risk" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Vehicle Unit</label>
                                <input type="text" class="form-control" id="vehicle_unit" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Type of Body</label>
                                <input type="text" class="form-control" id="type_of_body" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Plate No</label>
                                <input type="text" class="form-control" id="plate_no" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Engine No</label>
                                <input type="text" class="form-control" id="engine_no" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Chassis No</label>
                                <input type="text" class="form-control" id="chassis_no" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Renewal Premium</label>
                                <input type="text" class="form-control" id="renewal_premium" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Renewal TSI</label>
                                <input type="text" class="form-control" id="renewal_tsi" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Renewal Status</label>
                                <input type="text" class="form-control" id="renewal_status" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Location of Risk (2)</label>
                                <input type="text" class="form-control" id="location_of_risk_2" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mailing Address</label>
                                <input type="text" class="form-control" id="mailing_address" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">LGT Rate Per Branch</label>
                                <input type="text" class="form-control" id="lgt_rate_per_branch" readonly>
                            </div>
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" id="id">
                        <input type="hidden" id="policy_summary_id">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
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

            var parameter = '?page=1&limit=' + limit + '&account_id=' + account_id;

            window.location.replace(link + parameter);
        });
    });
</script>
<script type="text/javascript">

    $(document).ready(function() {
        $(document).on('click', '.import', function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var display = $('#modal-import .modal-body');
            var redirect = "<?php echo getCurrentUrl(); ?>";

            $.ajax({
                url: '/underwriting/import-renewal-upload/',
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

        $('.showModal').click(function(){
            let id = $(this).data('id')
            $.ajax({
                url: '/underwriting/renewal_json/',
                method: 'GET',
                data: {
                    id: id
                },
                success: function(data){
                   for(const index in data){
                       $('#'+index).val(data[index])
                   }
                }
            });
        });
    });
</script>