<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="panel_s">
    <div class="panel-body">
        <h4 class="no-margin"><?php echo _l('vendor_information'); ?></h4>
        <hr>
        <br>

        <div class="table-responsive s_table">
            <table class="table items no-mtop">
                <thead>
                    <tr>
                        <th><?php echo _l('client'); ?></th>
                        <th><?php echo _l('type_of_project'); ?></th>
                        <th><?php echo _l('location'); ?></th>
                        <th><?php echo _l('mini_contractor'); ?></th>
                        <th><?php echo _l('scope_of_works'); ?></th>
                        <th><?php echo _l('contract_prices'); ?></th>
                        <th><?php echo _l('start_date'); ?></th>
                        <th><?php echo _l('end_date'); ?></th>
                        <th><?php echo _l('size_of_project'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendor_work_completed as $value) : ?>
                        <tr>
                            <td><?= $value['client']; ?></td>
                            <td><?= $value['type_of_project']; ?></td>
                            <td><?= $value['location']; ?></td>
                            <td><?= $value['mini_contractor']; ?></td>
                            <td><?= $value['scope_of_works']; ?></td>
                            <td><?= $value['contract_prices']; ?></td>
                            <td><?= $value['start_date']; ?></td>
                            <td><?= $value['end_date']; ?></td>
                            <td><?= $value['size_of_project']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="table-responsive s_table">
            <table class="table items no-mtop">
                <thead>
                    <tr>
                        <th><?php echo _l('client'); ?></th>
                        <th><?php echo _l('type_of_project'); ?></th>
                        <th><?php echo _l('location'); ?></th>
                        <th><?php echo _l('mini_contractor'); ?></th>
                        <th><?php echo _l('scope_of_works'); ?></th>
                        <th><?php echo _l('contract_prices'); ?></th>
                        <th><?php echo _l('start_date'); ?></th>
                        <th><?php echo _l('proposed_end_date'); ?></th>
                        <th><?php echo _l('building_height'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendor_work_progress as $value) : ?>
                        <tr>
                            <td><?= $value['client']; ?></td>
                            <td><?= $value['type_of_project']; ?></td>
                            <td><?= $value['location']; ?></td>
                            <td><?= $value['mini_contractor']; ?></td>
                            <td><?= $value['scope_of_works']; ?></td>
                            <td><?= $value['contract_prices']; ?></td>
                            <td><?= $value['start_date']; ?></td>
                            <td><?= $value['end_date']; ?></td>
                            <td><?= $value['size_of_project']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
