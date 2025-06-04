<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="col-md-12">

          <div class="row">
             <div class="col-md-12">
                <h4 class="no-margin font-bold"><i class="fa fa-clipboard" aria-hidden="true"></i> <?php echo _l('daily_progress_report'); ?></h4>
                <hr />
             </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="table-responsive s_table">
                <table class="table items no-mtop" style="border: 1px solid #dee2e6;">
                    <tbody>
                        <?php 
                        if(!empty($sub_type_array)) {
                            foreach ($sub_type_array as $key => $value) { 
                              if(isset($value['name'])) {
                                $name = $value['name'];
                                unset($value['name']);
                              }
                              if(isset($value['is_bold'])) {
                                $is_bold = $value['is_bold'];
                                unset($value['is_bold']);
                              }
                              ?>
                                <tr<?php echo $is_bold ? ' style="font-weight: bold; background: #f1f5f9; color: #1e293b;"' : ''; ?>>
                                    <td align="left">
                                        <?php echo $name; ?>
                                    </td>
                                    <?php 
                                    foreach ($value as $vkey => $vvalue) { ?>
                                      <td align="right">
                                        <?php echo $vvalue; ?>
                                      </td>
                                    <?php } ?>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="col-md-8">
            </div>
          </div>

          <div class="row">
            <div class="col-md-5">
              <div class="table-responsive s_table">
                <table class="table items no-mtop" style="border: 1px solid #dee2e6;">
                    <tbody>
                        <?php 
                        if(!empty($type_array)) {
                            foreach ($type_array as $key => $value) { 
                              if(isset($value['name'])) {
                                $name = $value['name'];
                                unset($value['name']);
                              }
                              if(isset($value['is_bold'])) {
                                $is_bold = $value['is_bold'];
                                unset($value['is_bold']);
                              }
                              ?>
                                <tr<?php echo $is_bold ? ' style="font-weight: bold; background: #f1f5f9; color: #1e293b;"' : ''; ?>>
                                    <td align="left">
                                        <?php echo $name; ?>
                                    </td>
                                    <?php 
                                    foreach ($value as $vkey => $vvalue) { ?>
                                      <td align="right">
                                        <?php echo $vvalue; ?>
                                      </td>
                                    <?php } ?>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>
                </table>
              </div>
            </div>
            <div class="col-md-7">
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>
