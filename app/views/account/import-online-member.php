<div class="br-mainpanel mg-0">
    <div class="br-pagebody pd-0 mg-0">
      <div class="online-member-nav">
        <ul class="nav nav-tabs nav-tabs-style-1 nav-justified tx-13" role="tablist">
            <li class="nav-item">
              <a class="nav-link pd-y-10 btn-show-online-member active show" data-toggle="tab" href="#tab-online" role="tab" aria-selected="true">Online <small>(<?php echo countArray($data['online']); ?>)</small></a>
            </li>
            <li class="nav-item">
              <a class="nav-link pd-y-10 btn-show-online-member " data-toggle="tab" href="#tab-offline" role="tab" aria-selected="false">Offline <small>(<?php echo countArray($data['offline']); ?>)</small></a>
            </li>
        </ul>
      </div>
      <div class="tab-content">
        <div class="tab-pane active show" id="tab-online" role="tabpanel">
          <table class="table table-responsive d-md-table online-member-table mg-b-0 tx-12">
              <tbody>
                <?php
                    if (isset($data['online'])) {
                      foreach ($data['online'] as $key => $value) {
                        echo '<tr>
                                  <td class="wd-10p pd-l-20">
                                    <img src="'.displayImage($value['photo'], 'account').'" class="wd-36 rounded-circle" alt="Image">
                                  </td>
                                  <td>
                                    <a href="" class="tx-inverse tx-14 tx-medium d-block">'.(!empty($value['alias']) ? $value['alias'] : $value['full_name']).'</a>
                                    <span class="tx-11 d-block"><span class="square-8 bg-success mg-r-5 rounded-circle"></span>Online</span>
                                  </td>
                              </tr>';
                      }
                    }else{

                        echo '<tr class="online-member-table-empty-row">
                                  <td class="tx-center wd-10p pd-l-20 pd-y-20" colspan="3">
                                    There is no online member.
                                  </td>
                              </tr>';
                    }
                ?>
              </tbody>
          </table>
        </div>
        <div class="tab-pane" id="tab-offline" role="tabpanel">
          <table class="table table-responsive d-md-table online-member-table mg-b-0 tx-12">
              <tbody>
                <?php
                    if (isset($data['offline'])) {
                      foreach ($data['offline'] as $key => $value) {
                        echo '<tr>
                                  <td class="wd-10p pd-l-20">
                                    <img src="'.displayImage($value['photo'], 'account').'" class="wd-36 rounded-circle" alt="Image">
                                  </td>
                                  <td>
                                    <a href="" class="tx-inverse tx-14 tx-medium d-block">'.(!empty($value['alias']) ? $value['alias'] : $value['full_name']).'</a>
                                    <span class="tx-11 d-block"><span class="square-8 bg-warning mg-r-5 rounded-circle"></span>Offline</span>
                                  </td>
                              </tr>';
                      }
                    }else{

                        echo '<tr class="online-member-table-empty-row">
                                  <td class="tx-center wd-10p pd-l-20 pd-y-20" colspan="3">
                                    There is no offline member.
                                  </td>
                              </tr>';
                    }
                ?>
              </tbody>
          </table>
        </div>
      </div>
    </div>
</div>

