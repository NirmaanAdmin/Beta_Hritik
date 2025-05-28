<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Critical Tracker</title>
    <style>
        /* General table styling */
        .table {
            font-size: 10px !important;
            table-layout: fixed !important;
            width: 100% !important;
            word-wrap: break-word !important;
            border-collapse: collapse !important;
        }

        /* Borders */
        .border_table,
        .border_tr,
        .border_td,
        .border_td_left,
        .border_td_right {
            border: 1px solid #A4A4A4 !important;
        }

        /* Table cells */
        .table th,
        .table td {
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            white-space: normal !important;
            padding: 2px !important;
        }

        /* Alignment classes */
        .border_td {
            text-align: center;
        }

        .border_td_left {
            text-align: left;
        }

        .border_td_right {
            text-align: right;
        }

        /* Header styling */
        .thead-dark {
            background-color: #415164 !important;
            color: #fff !important;
        }

        /* Font weights */
        .font_500 {
            font-weight: 500;
        }

        /* Width classes */
        .width_20 {
            width: 20%;
        }

        .td_width_25 {
            width: 15%;
        }

        .td_width_55 {
            width: 60%;
        }

        .td_width_75 {
            width: 75%;
        }

        /* Heading styles */
        .h2_style {
            font-size: 35px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .h4_style {
            font-size: 25px;
            margin-bottom: 10px;
        }

        /* Other classes */
        .align_cen {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .td_appr {
            text-align: center;
            text-transform: uppercase;
        }

        .div_pdf {
            width: 680px !important;
        }

        .img_style {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>
    <?php
    $get_critical_tracker = get_critical_tracker_pdf();
    $html = '';
    $html .= '<table class="table purorder-item" style="width: 100%">
        <thead>
          <tr>
            <th class="thead-dark" align="left" style="width: 2%">' . _l('#') . '</th>
            <th class="thead-dark" align="left" style="width: 6.6%">' . _l('Department') . '</th>
            <th class="thead-dark" align="left" style="width: 6.6%">' . _l('Area/Head') . '</th>
            <th class="thead-dark" align="left" style="width: 11.5%">' . _l('Description') . '</th>
            <th class="thead-dark" align="left" style="width: 11.5%">' . _l('Decision') . '</th>
            <th class="thead-dark" align="left" style="width: 11.5%">' . _l('Action') . '</th>
            <th class="thead-dark" align="left" style="width: 7.7%">' . _l('Action By') . '</th>
            <th class="thead-dark" align="left" style="width: 6.6%">' . _l('Project') . '</th>
            <th class="thead-dark" align="left" style="width: 6.6%">' . _l('Target Date') . '</th>
            <th class="thead-dark" align="left" style="width: 6.6%">' . _l('Date Closed') . '</th>
            <th class="thead-dark" align="left" style="width: 6.6%">' . _l('Status') . '</th>
            <th class="thead-dark" align="left" style="width: 6.6%">' . _l('Priority') . '</th>
            <th class="thead-dark" align="left" style="width: 9.6%">' . _l('Fetched From') . '</th>
          </tr>
          </thead>
          <tbody>';
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
    foreach ($get_critical_tracker as $key => $item) {
        $target_date = !empty($item['target_date']) ? date('d M, Y', strtotime($item['target_date'])) : '';
        $date_closed = !empty($item['date_closed']) ? date('d M, Y', strtotime($item['date_closed'])) : '';
        $staff_name = trim(($item['firstname'] ?? '') . ' ' . ($item['lastname'] ?? ''));
        $status = $status_labels[$item['status']];
        $priority = $priority_labels[$item['priority']];
        $html .= '<tr>
            <td style="width: 2%">' . ($key + 1) . '</td>
            <td style="width: 6.6%">' . ($item['department_name'] ?? '') . '</td>
            <td style="width: 6.6%">' . ($item['area'] ?? '') . '</td>
            <td style="width: 11.5%">' . ($item['description'] ?? '') . '</td>
            <td style="width: 11.5%">' . ($item['decision'] ?? '') . '</td>
            <td style="width: 11.5%">' . ($item['action'] ?? '') . '</td>
            <td style="width: 6.6%">' . $staff_name . '</br>' . $item['vendor'] . '</td>
            <td style="width: 6.6%">' . get_project_name_by_id_mom($item['project_id']) . '</td>
            <td style="width: 6.6%">' . $target_date . '</td>
            <td style="width: 6.6%">' . $date_closed . '</td>
            <td style="width: 6.6%">' . ($status['text'] ?? '') . '</td>
            <td style="width: 6.6%">' . ($priority['text'] ?? '') . '</td>
            <td style="width: 9.6%">' . get_meeting_name_by_id($item['minute_id']) . '</td>
        </tr>';
    }

    $html .= '</tbody>
      </table>';

    echo $html;
    ?>
</body>

</html>