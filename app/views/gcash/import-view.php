    <div class="br-mainpanel mg-0">
        <div class="br-pagebody pd-0 mg-0">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
                    <h6 class="br-section-label tx-info mg-t-0">LEAD DETAILS</h6>
                    <table class="table table-bordered bd mg-b-0">
                        <tbody>
                            <tr>
                                <td class="wd-30p">Country</td>
                                <td class="wd-70p tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'country_name'); ?></td>
                            </tr>
                            <tr>
                                <td>Source</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'source_name'); ?></td>
                            </tr>
                            <tr>
                                <td>Industry</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'industry_name'); ?></td>
                            </tr>
                            <tr>
                                <td>Sub-category</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'industry_sub_category_name'); ?></td>
                            </tr>
                            <tr>
                                <td>Business Model</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'business_model_name'); ?></td>
                            </tr>
                            <tr>
                                <td>Sub Source</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'sub_source_name'); ?></td>
                            </tr>
                            <tr>
                                <td>Campaign</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'campaign_name'); ?></td>
                            </tr>
                            <tr>
                                <td>e-Commerce</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'e_commerce'); ?></td>
                            </tr>
                            <tr>
                                <td>Parent Company Name</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'parent_company_name'); ?></td>
                            </tr>
                            <tr>
                                <td>Company Name</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'name'); ?></td>
                            </tr>
                            <tr>
                                <td>Alias</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'aka'); ?></td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'address'); ?></td>
                            </tr>
                            <tr>
                                <td>Postal Code</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'postal_code'); ?></td>
                            </tr>
                            <tr>
                                <td>Office Number</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'office_no'); ?></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'email'); ?></td>
                            </tr>
                            <tr>
                                <td>Email CC</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'email_cc'); ?></td>
                            </tr>
                            <tr>
                                <td>Website Url</td>
                                <td class="tx-bold"><a href="<?php echo multiArrayKeyExist($data, 'company', 'website_url'); ?>" target="_blank" class="tx-default"><?php echo multiArrayKeyExist($data, 'company', 'website_url'); ?></a></td>
                            </tr>
                            <tr>
                                <td>Landing Page</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'landing_page'); ?></td>
                            </tr>
                            <tr>
                                <td>Inquiry Page</td>
                                <td class="tx-bold"><?php echo multiArrayKeyExist($data, 'company', 'inquiry_page'); ?></td>
                            </tr>
                            <?php 
                                if(!empty(multiArrayKeyExist($data, 'company', 'logo_file'))){
                                    echo '
                                            <tr>
                                                <td>Logo</td>
                                                <td class="tx-bold"><img src="'.displayImage(multiArrayKeyExist($data, 'company', 'logo_file'), 'company').'" class="w-300px h-auto img-fluid"></td>
                                            </tr>
                                         ';
                                }
                            ?>
                        </tbody>
                    </table>
                    <?php if(!empty(multiArrayKeyExist($data, 'company', 'remarks'))){ ?>
                        <h6 class="br-section-label tx-info mg-b-5 mg-t-20">NOTES</h6>
                        <div class="card pd-20">
                            <?php echo multiArrayKeyExist($data, 'company', 'remarks'); ?>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
                    <h6 class="br-section-label tx-info mg-t-0">Client Details</h6>
                    <?php
                        if(is_array($data['client'])){
                            foreach($data['client'] as $key => $value){
                                echo '
                                        <table class="table table-bordered bd">
                                            <tbody>
                                                <tr>
                                                    <td class="wd-35p">Name</td>
                                                    <td class="wd-65p tx-bold">'.htmlDecode($value['client_name']).'</td>
                                                </tr>
                                                <tr>
                                                    <td>Contact Number</td>
                                                    <td class="tx-bold">'.htmlDecode($value['contact_no']).'</td>
                                                </tr>
                                                <tr>
                                                    <td>Email Address</td>
                                                    <td class="tx-bold">'.htmlDecode($value['email']).'</td>
                                                </tr>
                                                <tr>
                                                    <td>Designation</td>
                                                    <td class="tx-bold">'.htmlDecode($value['designation']).'</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                     ';
                            }
                        }else{
                            echo '<p>No client added</p>';
                        }
                    ?>
                </div>
            </div>

        </div>
    </div>