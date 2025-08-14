            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">

              <!-- Contact -->
              <div class="row my-6">
                <div class="col-12 text-center my-6">
                  <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Contact Number</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($data['handlers'] as $handler){
                            ?>
                                    <tr>
                                        <td><?=$handler['first_name'] . " " . $handler['last_name']?></td>
                                        <td><?=$handler['email']?></td>
                                        <td><?=$handler['contact_number']?></td>
                                        <td></td>
                                    </tr>
                            <?php
                                }
                            ?>
                        </tbody>
                  </table>
                </div>
              </div>
              <!-- /Contact -->
            </div>
            <!-- / Content -->
