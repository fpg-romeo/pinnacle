            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">

              <!-- Contact -->
              <div class="row my-6">
                <div class="col-12 text-center my-6">
                  <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                foreach($data['segments'] as $segment){
                            ?>
                                    <tr>
                                        <td><?=$segment['code']?></td>
                                        <td><?=$segment['name']?></td>
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
