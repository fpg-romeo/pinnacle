<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">Underwriting <span class="text-primary">[ Renewal ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                <button class="btn btn-info me-4">
                    <i class="icon-base ti tabler-upload me-2"></i>
                    <span class="align-middle">Upload</span>
                </button>
                <a href="/collection/manage" class="btn btn-primary text-white">
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
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Client</th>
                                        <th>Users</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
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
                                </tbody>
                            </table>
                        </div>

                        <div class="row justify-content-between">
                            <div class="d-md-flex justify-content-between align-items-center dt-layout-start col-md-auto me-auto mt-0">
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
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>