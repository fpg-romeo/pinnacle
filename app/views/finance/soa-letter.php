<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">SOA <span class="text-primary">[ Letters ]</span></h4>
            </div>
            <div class="mb-6 col-lg-6 col-xl-6 col-12 mb-0 text-end">
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Aging</th>
                                        <th class="text-center">Letter</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Date Created</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="table-border-bottom-0">
                                    <?php
                                        if(isset($data['letter']) && is_array($data['letter'])){
                                            foreach($data['letter'] as $letter){
                                    ?>
                                                <tr>
                                                    <td><?= $letter['max_age'] == -1 ? $letter['min_age'] . " DPD and Above" : $letter['min_age'] . "-" . $letter['max_age'] . " DPD"?></td>
                                                    <td>
                                                        <span><?=$letter['name']?></span><br>
                                                        <small><?=$letter['frequency_label']?></small>
                                                    </td>
                                                    <td class="text-center"><?=$letter['is_active'] ? "Active" : "Inactive"?></td>
                                                    <td><?=date("F d, Y", strtotime($letter['created_at']))?></td>
                                                    <td class="text-center">
                                                         <a class="showModal" href="/finance/soa-letter/<?=$letter['id'];?>"><i class="icon-base ti tabler-eye me-1"></i></a>
                                                    </td>
                                                </tr>
                                    <?php
                                            }
                                        }else{
                                            echo '<tr><td colspan="7" class="text-center">No record found</td></tr>';
                                        }
                                    ?> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>