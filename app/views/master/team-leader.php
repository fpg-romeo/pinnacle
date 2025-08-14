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
                                foreach($data['team_leaders'] as $team_leader){
                            ?>
                                    <tr>
                                        <td><?=$team_leader['first_name'] . " " . $team_leader['last_name']?></td>
                                        <td><?=$team_leader['email']?></td>
                                        <td><?=$team_leader['contact_number']?></td>
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
