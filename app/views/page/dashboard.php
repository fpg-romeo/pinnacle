    <div class="br-mainpanel">
        <div class="br-pageheader">
            <nav class="breadcrumb pd-0 mg-0 tx-12">
                <a class="breadcrumb-item" href="/">Dashboard</a>
            </nav>
        </div>
        <div class="br-pagetitle pos-relative">
            <i class="icon fa fa-pie-chart hidden-xs-down"></i>
            <div>
                <h4><?php echo ACCOUNT_DEPARTMENT_NAME; ?> Dashboard</h4>
                <p class="mg-b-0">Data overview as of <?php echo date('F d, Y'); ?> - <?php echo date('h:i:s A'); ?></p>
                <div class="pagetitle-button">

                </div>
            </div>
        </div>
        <div class="br-pagebody">
            <?php flash(promptMessage('message')); ?>
        </div>
    </div>