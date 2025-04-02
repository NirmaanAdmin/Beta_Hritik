<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="panel_s">
      <div class="panel-body">
        <div class="horizontal-scrollable-tabs">
          <nav>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <?php
              $i = 0;
              foreach($tab as $val) {
                ?>
                <li<?php if($i == 0){echo " class='active'"; } ?>>
                <a href="<?php echo admin_url('purchase/purchase_dashboard?group='.$val); ?>" data-group="<?php echo html_entity_decode($val); ?>">
                  <?php echo _l($val); ?></a>
                </li>
                <?php $i++; 
              } ?>
            </ul>
          </nav>
        </div>
        </div>
      </div>
      
      <?php $this->load->view($tabs['view']); ?>
      
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>

<?php if($group == 'purchase_order') {
  require 'modules/purchase/assets/js/purchase_dashboard/purchase_order_js.php';
} else if($group == 'work_order') {
  require 'modules/purchase/assets/js/purchase_dashboard/work_order_js.php';
} ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
