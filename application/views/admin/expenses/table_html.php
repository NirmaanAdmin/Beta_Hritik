<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
$hasPermission = staff_can('edit', 'expenses') || staff_can('edit', 'expenses');
if ($withBulkActions === true && $hasPermission) { ?>
  <a href="#" data-toggle="modal" data-target="#expenses_bulk_actions" class="hide bulk-actions-btn table-btn"
      data-table=".table-expenses">
      <?php echo _l('bulk_actions'); ?>
  </a>
<?php } ?>

<div class="">
  <table data-last-order-identifier="expenses" data-default-order="" id="expenses" class="dt-table-loading table table-expenses">
    <thead>
      <tr>
        <th class=""><span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="expenses"><label></label></div></th>
        <th><?php echo _l('the_number_sign'); ?></th>
        <th><?php echo _l('expense_dt_table_heading_category'); ?></th>
        <th><?php echo _l('expense_dt_table_heading_amount'); ?></th>
        <th><?php echo _l('expense_name'); ?></th>
        <th><?php echo _l('receipt'); ?></th>
        <th><?php echo _l('expense_dt_table_heading_date'); ?></th>
        <th><?php echo _l('project'); ?></th>
        <th><?php echo _l('expense_dt_table_heading_customer'); ?></th>
        <th><?php echo _l('invoice'); ?></th>
        <th><?php echo _l('expense_dt_table_heading_reference_no'); ?></th>
        <th><?php echo _l('expense_dt_table_heading_payment_mode'); ?></th>
        <th>Vendor</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <td></td>
      <td></td>
      <td></td>
      <td class="total_expense_amount"></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tfoot>
  </table>
</div>

<?php
echo $this->view('admin/expenses/_bulk_actions_modal');
?>