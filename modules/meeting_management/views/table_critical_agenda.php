<?php
defined('BASEPATH') or exit('No direct script access allowed');
$module_name = 'critical_mom';

// Define filter names
$department_filter_name = 'department';
$status_filter_name     = 'status';
$priority_filter_name   = 'priority';
$from_date_filter_name  = 'from_date';
$to_date_filter_name    = 'to_date';

// Columns for dataTables
$aColumns      = [
    'id',
    'department',
    'area',
    'description',
    'decision',
    'action',
    'staff',
    'vendor',
    'target_date',
    'date_closed',
    'status',
    'priority',
];
$sIndexColumn  = 'id';
$sTable        = db_prefix() . 'critical_mom';
$join          = [
    'LEFT JOIN ' . db_prefix() . 'departments ON ' . db_prefix() . 'departments.departmentid = ' . db_prefix() . 'critical_mom.department',
    'LEFT JOIN ' . db_prefix() . 'staff       ON ' . db_prefix() . 'staff.staffid       = ' . db_prefix() . 'critical_mom.staff',
];
$where         = [];

// --- build filters ---
if ($from = $this->ci->input->post('from_date')) {
    $where[] = 'AND target_date >= "' . date('Y-m-d', strtotime($from)) . '"';
}
if ($to = $this->ci->input->post('to_date')) {
    $where[] = 'AND target_date <= "' . date('Y-m-d', strtotime($to)) . '"';
}
if ($depts = $this->ci->input->post('department')) {
    $where[] = 'AND department IN (' . implode(',', $depts) . ')';
}
if ($stats = $this->ci->input->post('status')) {
    $where[] = 'AND status IN (' . implode(',', $stats) . ')';
}
if ($prios = $this->ci->input->post('priority')) {
    $where[] = 'AND priority IN (' . implode(',', $prios) . ')';
}

// persist filters
update_module_filter($module_name, $department_filter_name, !empty($depts) ? implode(',', $depts) : null);
update_module_filter($module_name, $status_filter_name,     !empty($stats) ? implode(',', $stats) : null);
update_module_filter($module_name, $priority_filter_name,   !empty($prios) ? implode(',', $prios) : null);
update_module_filter($module_name, $from_date_filter_name,  !empty($from)  ? $from  : null);
update_module_filter($module_name, $to_date_filter_name,    !empty($to)    ? $to    : null);

// fetch data
$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'critical_mom.id',
    db_prefix() . 'departments.name as department_name',
    db_prefix() . 'staff.firstname',
    db_prefix() . 'staff.lastname',
]);
$output  = $result['output'];
$rResult = $result['rResult'];

// load dept list
$this->ci->load->model('departments_model');
$departments       = $this->ci->departments_model->get();
$departments_by_id = array_column($departments, null, 'departmentid');

// label maps
$status_labels = [
    1 => ['label' => 'danger',  'table' => 'open',  'text' => _l('Open')],
    2 => ['label' => 'success', 'table' => 'close', 'text' => _l('Close')],
];
$priority_labels = [
    1 => ['label' => 'warning', 'table' => 'high',   'text' => _l('High')],
    2 => ['label' => 'default', 'table' => 'low',    'text' => _l('Low')],
    3 => ['label' => 'info',    'table' => 'medium', 'text' => _l('Medium')],
    4 => ['label' => 'danger',  'table' => 'urgent', 'text' => _l('Urgent')],
];

// build rows
$i = 1;
foreach ($rResult as $aRow) {
    $row = [];
    // 1) Serial
    $row[] = $i++;

    // 2) Department dropdown
    $department_html = '';
    if (isset($departments_by_id[$aRow['department']])) {
        $dept = $departments_by_id[$aRow['department']];
        $department_html  = '<span class="inline-block label label-default"'
            . ' id="department_span_' . $aRow['id'] . '"'
            . ' data-task-status="department">'
            . $dept['name'];
    }
    $department_html .= '<div class="dropdown inline-block mleft5 table-export-exclude">'
        . '<a href="#" class="dropdown-toggle text-dark" data-toggle="dropdown"'
        . ' aria-haspopup="true" aria-expanded="false">'
        . '<i class="fa fa-caret-down" data-toggle="tooltip" title="'
        . _l('change_department') . '"></i></a>';
    $department_html .= '<ul class="dropdown-menu dropdown-menu-right">';
    foreach ($departments_by_id as $id => $d) {
        if ($id != $aRow['department']) {
            $department_html .= '<li><a href="#"'
                . ' onclick="change_department('
                . $id . ', ' . $aRow['id']
                . '); return false;">'
                . $d['name'] . '</a></li>';
        }
    }
    $department_html .= '</ul></div></span>';

    $row[] = $department_html;

    // 3) Area, 4) Description, 5) Decision, 6) Action
    if (!empty($aRow['area'])) {
        $area = '<span class="area-display" data-id="' . $aRow['id'] . '">' . $aRow['area'] . '</span>';
    } else {
        $area = '<textarea '
            . 'class="form-control area-input" '
            . 'placeholder="Enter area" '
            . 'data-id="' . $aRow['id'] . '" '
            . 'rows="3"></textarea>';
    }

    $row[] = $area;

    if (!empty($aRow['description'])) {
        $description = '<span class="description-display" data-id="' . $aRow['id'] . '">'
            . html_escape($aRow['description'])
            . '</span>';
    } else {
        $description = '<textarea '
            . 'class="form-control description-input" '
            . 'placeholder="Enter description" '
            . 'data-id="' . $aRow['id'] . '" '
            . 'rows="3" cols="80"></textarea>';
    }
    $row[] = $description;


    if (!empty($aRow['decision'])) {
        $decision = '<span class="decision-display" data-id="' . $aRow['id'] . '">'
            . html_escape($aRow['decision'])
            . '</span>';
    } else {
        $decision = '<textarea '
            . 'class="form-control decision-input" '
            . 'placeholder="Enter decision" '
            . 'data-id="' . $aRow['id'] . '" '
            . 'rows="4" cols="80"></textarea>';
    }

    $row[] = $decision;

    if (!empty($aRow['action'])) {
        $action = '<span class="action-display" data-id="' . $aRow['id'] . '">'
            . html_escape($aRow['action'])
            . '</span>';
    } else {
        $action = '<textarea '
            . 'class="form-control action-input" '
            . 'placeholder="Enter action" '
            . 'data-id="' . $aRow['id'] . '" '
            . 'rows="4" cols="80"></textarea>';
    }


    $row[] = $action;

    // 7) Action By (staff + vendor)
    $action_by = '';
    if ($aRow['staff'] > 0) {
        $action_by .= $aRow['firstname'] . ' ' . $aRow['lastname'] . '<br>';
    }
    $action_by .= $aRow['vendor'];
    $row[] = $action_by;

    // 8) Target Date
    $row[] = !empty($aRow['target_date'])
        ? date('d M, Y', strtotime($aRow['target_date']))
        : '';

    // 9) Date Closed (editable)
    $row[] =  '<input type="date" class="form-control closed-date-input"'
        . ' value="' . $aRow['date_closed'] . '" data-id="' . $aRow['id'] . '">';


    // 10) Status dropdown
    $status_html = '';
    if (isset($status_labels[$aRow['status']])) {
        $s = $status_labels[$aRow['status']];
        $status_html  = '<span class="inline-block label label-' . $s['label'] . '"'
            . ' id="status_span_' . $aRow['id'] . '"'
            . ' data-task-status="' . $s['table'] . '">'
            . $s['text'];
    }
    $status_html .= '<div class="dropdown inline-block mleft5 table-export-exclude">'
        . '<a href="#" class="dropdown-toggle text-dark" id="tableStatus-'
        . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true"'
        . ' aria-expanded="false">'
        . '<i class="fa fa-caret-down" data-toggle="tooltip" title="'
        . _l('change_status') . '"></i></a>';
    $status_html .= '<ul class="dropdown-menu dropdown-menu-right"'
        . ' aria-labelledby="tableStatus-' . $aRow['id'] . '">';
    foreach ($status_labels as $key => $lbl) {
        if ($key != $aRow['status']) {
            $status_html .= '<li><a href="#"'
                . ' onclick="change_status_mom('
                . $key . ', ' . $aRow['id']
                . '); return false;">'
                . $lbl['text'] . '</a></li>';
        }
    }
    $status_html .= '</ul></div></span>';

    $row[] = $status_html;

    // 11) Priority dropdown
    $priority_html = '';
    if (isset($priority_labels[$aRow['priority']])) {
        $p = $priority_labels[$aRow['priority']];
        $priority_html  = '<span class="inline-block label label-' . $p['label'] . '"'
            . ' id="priority_span_' . $aRow['id'] . '"'
            . ' data-task-status="' . $p['table'] . '">'
            . $p['text'];
    }
    $priority_html .= '<div class="dropdown inline-block mleft5 table-export-exclude">'
        . '<a href="#" class="dropdown-toggle text-dark" id="tablePriority-'
        . $aRow['id'] . '" data-toggle="dropdown" aria-haspopup="true"'
        . ' aria-expanded="false">'
        . '<i class="fa fa-caret-down" data-toggle="tooltip" title="'
        . _l('change_priority') . '"></i></a>';
    $priority_html .= '<ul class="dropdown-menu dropdown-menu-right"'
        . ' aria-labelledby="tablePriority-' . $aRow['id'] . '">';
    foreach ($priority_labels as $key => $lbl) {
        if ($key != $aRow['priority']) {
            $priority_html .= '<li><a href="#"'
                . ' onclick="change_priority_mom('
                . $key . ', ' . $aRow['id']
                . '); return false;">'
                . $lbl['text'] . '</a></li>';
        }
    }
    $priority_html .= '</ul></div></span>';

    $row[] = $priority_html;

    $output['aaData'][] = $row;
}
