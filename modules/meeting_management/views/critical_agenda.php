<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head();  ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s invoice-item-table">
                    <div class="panel-body">
                        <div class="row" style="display: flex;">
                            <h4><?php echo _l('meeting_critical_agenda'); ?></h4>
                            <button class="btn btn-info pull-right mright10 display-block" style="margin-left: 10px;" data-toggle="modal" data-target="#addNewRowModal">
                                <i class="fa fa-plus"></i> <?php echo _l('New'); ?>
                            </button>
                        </div>


                        <table class="table table-bordered table-table_critical_tracker">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Department</th>
                                    <th>Area/Head</th>
                                    <th>Description</th>
                                    <th>Decision</th>
                                    <th>Action</th>
                                    <th>Action By</th>
                                    <th>Target Date</th>
                                    <th>Date Closed</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody id="critical-agenda-tbody">
                                <?php if (!empty($critical_agenda)) : ?>
                                    <?php $serial = 1;
                                    $departments_by_id = array_column($department, null, 'departmentid');
                                    $target_date = $date_closed = $status = ''; ?>
                                    <?php foreach ($critical_agenda as $key => $agenda) :

                                        if (!empty($agenda['target_date'])) {
                                            $target_date = date('d M, Y', strtotime($agenda['target_date']));
                                        } else {
                                            $target_date = '';
                                        }

                                        if (!empty($agenda['date_closed'])) {
                                            $date_closed = date('d M, Y', strtotime($agenda['date_closed']));
                                        } else {
                                            $date_closed = '';
                                        }


                                        $status_labels = [
                                            1 => ['label' => 'danger', 'table' => 'open', 'text' => _l('Open')],
                                            2 => ['label' => 'success', 'table' => 'close', 'text' => _l('close')],
                                        ];
                                        // Start generating the HTML
                                        $status_html = '';
                                        if (isset($status_labels[$agenda['status']])) {
                                            $status = $status_labels[$agenda['status']];
                                            $status_html = '<span class="inline-block label label-' . $status['label'] . '" id="status_span_' . $agenda['id'] . '" task-status-table="' . $status['table'] . '">' . $status['text'];
                                        }


                                        $status_html .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                                        $status_html .= '<a href="#" class="dropdown-toggle text-dark" id="tablePurOderStatus-' . $agenda['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                        $status_html .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
                                        $status_html .= '</a>';

                                        $status_html .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tablePurOderStatus-' . $agenda['id'] . '">';

                                        foreach ($status_labels as $key => $status) {
                                            if ($key != $agenda['status']) {
                                                $status_html .= '<li>
                                                     <a href="#" onclick="change_status_mom(' . $key . ', ' . $agenda['id'] . '); return false;">
                                                           ' . $status['text'] . '
                                                     </a>
                                                  </li>';
                                            }
                                        }

                                        $status_html .= '</ul>';
                                        $status_html .= '</div>';
                                        $status_html .= '</span>';

                                        $priority_labels = [
                                            1 => ['label' => 'warning', 'table' => 'high', 'text' => _l('High')],
                                            2 => ['label' => 'default', 'table' => 'low', 'text' => _l('Low')],
                                            3 => ['label' => 'info', 'table' => 'medium', 'text' => _l('Medium')],
                                            4 => ['label' => 'danger', 'table' => 'urgent', 'text' => _l('Urgent')],
                                        ];
                                        // Start generating the HTML
                                        $priority_html = '';
                                        if (isset($priority_labels[$agenda['priority']])) {
                                            $priority = $priority_labels[$agenda['priority']];
                                            $priority_html = '<span class="inline-block label label-' . $priority['label'] . '" id="priority_span_' . $agenda['id'] . '" task-status-table="' . $priority['table'] . '">' . $priority['text'];
                                        }


                                        $priority_html .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                                        $priority_html .= '<a href="#" class="dropdown-toggle text-dark" id="tablePurOderPriority-' . $agenda['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                        $priority_html .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
                                        $priority_html .= '</a>';

                                        $priority_html .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tablePurOderPriority-' . $agenda['id'] . '">';

                                        foreach ($priority_labels as $key => $priority) {
                                            if ($key != $agenda['priority']) {
                                                $priority_html .= '<li>
                                                     <a href="#" onclick="change_priority_mom(' . $key . ', ' . $agenda['id'] . '); return false;">
                                                           ' . $priority['text'] . '
                                                     </a>
                                                  </li>';
                                            }
                                        }

                                        $priority_html .= '</ul>';
                                        $priority_html .= '</div>';
                                        $priority_html .= '</span>';


                                        // First, let's create a label configuration for departments
                                        $department_label_config = [
                                            'default' => ['label' => 'default', 'table' => 'department', 'text' => 'Department']
                                        ];

                                        // Start generating the HTML
                                        $department_html = '';
                                        if (isset($departments_by_id[$agenda['department']])) {
                                            $dept         = $departments_by_id[$agenda['department']];
                                            $label_config = $department_label_config['default'];

                                            $department_html  = '<span class="inline-block label label-'
                                                . $label_config['label']
                                                . '" id="department_span_'
                                                . $agenda['id']
                                                . '" task-status-table="'
                                                . $label_config['table']
                                                . '">'
                                                . $dept['name'];
                                        }
                                        // dropdown toggle
                                        $department_html .= '<div class="dropdown inline-block mleft5 table-export-exclude">'
                                            . '<a href="#" class="dropdown-toggle text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                            . '<span data-toggle="tooltip" title="' . _l('change_department') . '">'
                                            . '<i class="fa fa-caret-down"></i></span></a>';

                                        // menu items
                                        $department_html .= '<ul class="dropdown-menu dropdown-menu-right">';
                                        foreach ($departments_by_id as $id => $d) {
                                            if ($id != $agenda['department']) {
                                                $department_html .= '<li>'
                                                    . '<a href="#" onclick="change_department('
                                                    . $id . ', ' . $agenda['id']
                                                    . ');return false;">'
                                                    . $d['name']
                                                    . '</a></li>';
                                            }
                                        }
                                        $department_html .= '</ul></div></span>';


                                    ?>

                                        <tr>
                                            <td><?php echo $serial; ?></td>
                                            <td><?php echo $department_html; ?></td>
                                            <td><?php echo $agenda['area']; ?></td>
                                            <td><?php echo $agenda['description']; ?></td>
                                            <td><?php echo $agenda['decision']; ?></td>
                                            <td><?php echo $agenda['action']; ?></td>
                                            <td>
                                                <?php
                                                if ($agenda['staff'] > 0) {
                                                    echo getStaffNamesFromCSV($agenda['staff']) . '<br>';
                                                } ?>
                                                <?php echo $agenda['vendor']; ?>
                                            </td>
                                            <td><?php echo $target_date; ?></td>
                                            <td><?php echo '<input type="date" class="form-control closed-date-input" 
                        value="' . $agenda['date_closed'] . '" data-id="' . $agenda['id'] . '" ">'; ?></td>
                                            <td><?php echo $status_html; ?></td>
                                            <td><?php echo $priority_html; ?></td>
                                        </tr>
                                        <?php $serial++; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="11" class="text-center"><?php echo _l('no_records_found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<div class="modal fade" id="addNewRowModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 98%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('Add New'); ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!-- <div class="col-md-8 pull-right">
                    <div class="col-md-2 pull-right">
                        <div id="dowload_file_sample" style="margin-top: 22px;">
                            <label for="file_csv" class="control-label"> </label>
                            <a href="<?php echo site_url('modules/purchase/uploads/file_sample/Sample_import_order_tracker_en.xlsx') ?>" class="btn btn-primary">Template</a>
                        </div>
                    </div>
                    <div class="col-md-4 pull-right" style="display: flex;align-items: end;padding: 0px;">
                        <?php echo form_open_multipart(admin_url('purchase/import_file_xlsx_order_tracker_items'), array('id' => 'import_form')); ?>
                        <?php echo form_hidden('leads_import', 'true'); ?>
                        <?php echo render_input('file_csv', 'choose_excel_file', '', 'file'); ?>

                        <div class="form-group" style="margin-left: 10px;">
                            <button id="uploadfile" type="button" class="btn btn-info import" onclick="return uploadfilecsv(this);"><?php echo _l('import'); ?></button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                </div> -->
                <div class="col-md-12 ">
                    <div class="form-group pull-right" id="file_upload_response">

                    </div>

                </div>
                <div id="box-loading" class="pull-right">

                </div>
            </div>
            <div class="modal-body invoice-item">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" style="overflow-x: unset !important;">
                            <?php
                            echo form_open_multipart('', array('id' => 'critical_tracker-form'));
                            ?>
                            <table class="table critical-tracker-items-table items table-main-invoice-edit has-calculations no-mtop">
                                <thead>
                                    <tr>
                                    <tr>
                                        <th>Department</th>
                                        <th>Area/Head</th>
                                        <th><strong>Description</strong></th>
                                        <th><strong>Decision</strong></th>
                                        <th><strong>Action</strong></th>
                                        <th><strong>Action By</strong></th>
                                        <th width="5%"><strong>Target Date</strong></th>
                                        <th width="5%"><strong>Date Closed</strong></th>
                                        <th width="5%"><strong>Status</strong></th>
                                        <th width="5%"><strong>Priority</strong></th>
                                        <th width="3%"></th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody class="mom_body">
                                    <?php echo pur_html_entity_decode($mom_row_template); ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-info pull-right"><?php echo _l('Save'); ?></button>
                            </form>
                        </div>
                        <div id="removed-items"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<?php require 'modules/meeting_management/assets/js/critical_mom_js.php'; ?>
</body>

</html>

