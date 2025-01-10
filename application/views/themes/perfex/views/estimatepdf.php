<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('estimate_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $estimate_number . '</b>';

if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . estimate_status_color_pdf($status) . ');text-transform:uppercase;">' . format_estimate_status($status, '', false) . '</span>';
}

// Add logo
$info_left_column .= pdf_logo_url();
// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(10);

$organization_info = '<div style="color:#424242;">';
    $organization_info .= format_organization_info();
$organization_info .= '</div>';

// Estimate to
$estimate_info = '<b>' . _l('estimate_to') . '</b>';
$estimate_info .= '<div style="color:#424242;">';
$estimate_info .= format_customer_info($estimate, 'estimate', 'billing');
$estimate_info .= '</div>';

// ship to to
if ($estimate->include_shipping == 1 && $estimate->show_shipping_on_estimate == 1) {
    $estimate_info .= '<br /><b>' . _l('ship_to') . '</b>';
    $estimate_info .= '<div style="color:#424242;">';
    $estimate_info .= format_customer_info($estimate, 'estimate', 'shipping');
    $estimate_info .= '</div>';
}

$estimate_info .= '<br />' . _l('estimate_data_date') . ': ' . _d($estimate->date) . '<br />';

if (!empty($estimate->expirydate)) {
    $estimate_info .= _l('estimate_data_expiry_date') . ': ' . _d($estimate->expirydate) . '<br />';
}

if (!empty($estimate->reference_no)) {
    $estimate_info .= _l('reference_no') . ': ' . $estimate->reference_no . '<br />';
}

if ($estimate->sale_agent && get_option('show_sale_agent_on_estimates') == 1) {
    $estimate_info .= _l('sale_agent_string') . ': ' . get_staff_full_name($estimate->sale_agent) . '<br />';
}

if ($estimate->project_id && get_option('show_project_on_estimate') == 1) {
    $estimate_info .= _l('project') . ': ' . get_project_name_by_id($estimate->project_id) . '<br />';
}

foreach ($pdf_custom_fields as $field) {
    $value = get_custom_field_value($estimate->id, $field['id'], 'estimate');
    if ($value == '') {
        continue;
    }
    $estimate_info .= $field['name'] . ': ' . $value . '<br />';
}

$left_info  = $swap == '1' ? $estimate_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $estimate_info;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

// The Table
$pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 6));

// The items table
// $items = get_items_table_data($estimate, 'estimate', 'pdf');
// $tblhtml = $items->table();
// $pdf->writeHTML($tblhtml, true, false, false, false, '');

$tblfestimatehtml = '';
$tblfestimatehtml .= '<h3 style="text-align:center; ">Final estimate</h3>';
$tblfestimatehtml .= '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="8">';
$tblfestimatehtml .= '
<thead>
  <tr height="30" bgcolor="#323a45" style="color:#ffffff; font-size:14px;">
     <th width="7%;" align="center">'._l('the_number_sign').'</th>
     <th width="30%" align="left">'._l('sales_item').'</th>
     <th width="10%" align="right">'._l('estimate_table_quantity_heading').'</th>
     <th width="18%" align="right">'._l('estimate_table_rate_heading').'</th>
     <th width="15%" align="right">'._l('estimate_table_tax_heading').'</th>
     <th width="20%" align="right">'._l('estimate_table_amount_heading').'</th>
  </tr>
</thead>';
$tblfestimatehtml .= '<tbody>';
$tblfestimatehtml .= '
<tr style="font-size:13px;">
    <td width="7%;" align="center">1</td>
    <td width="30%" align="left;"><span style="font-size:13px;"><strong>'.$basic_estimate['final_estimate']['name'].'</strong></span></td>
    <td width="10%" align="right">'.$basic_estimate['final_estimate']['qty'].'</td>
    <td width="18%" align="right">'.app_format_money($basic_estimate['final_estimate']['subtotal'], $estimate->currency_name).'</td>
    <td width="15%" align="right">'.app_format_money($basic_estimate['final_estimate']['tax'], $estimate->currency_name).'</td>
    <td width="20%" align="right">'.app_format_money($basic_estimate['final_estimate']['amount'], $estimate->currency_name).'</td>
</tr>';
$tblfestimatehtml .= '</tbody>';
$tblfestimatehtml .= '</table>';

$pdf->writeHTML($tblfestimatehtml, true, false, false, false, '');

$tblsummaryhtml = '';
$tblsummaryhtml .= '<h3 style="text-align:center; ">Index - A</h3>';
$tblsummaryhtml .= '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="8">';
$tblsummaryhtml .= '
<thead>
  <tr height="30" bgcolor="#323a45" style="color:#ffffff; font-size:14px;">
     <th width="7%;" align="center">'._l('the_number_sign').'</th>
     <th width="30%" align="left">'._l('sales_item').'</th>
     <th width="10%" align="right">'._l('estimate_table_quantity_heading').'</th>
     <th width="18%" align="right">'._l('estimate_table_rate_heading').'</th>
     <th width="15%" align="right">'._l('estimate_table_tax_heading').'</th>
     <th width="20%" align="right">'._l('estimate_table_amount_heading').'</th>
  </tr>
</thead>';
$tblsummaryhtml .= '<tbody>';
$summary = $basic_estimate['summary'];
foreach($summary as $ikey => $svalue) {
    $tblsummaryhtml .= '
    <tr style="font-size:13px;">
        <td width="7%;" align="center">'.($ikey + 1).'</td>
        <td width="30%" align="left;"><span style="font-size:13px;"><strong>'.$svalue['name'].'</strong></span></td>
        <td width="10%" align="right">'.$svalue['qty'].'</td>
        <td width="18%" align="right">'.app_format_money($svalue['subtotal'], $estimate->currency_name).'</td>
        <td width="15%" align="right">'.app_format_money($svalue['tax'], $estimate->currency_name).'</td>
        <td width="20%" align="right">'.app_format_money($svalue['amount'], $estimate->currency_name).'</td>
    </tr>';
}
$tblsummaryhtml .= '</tbody>';
$tblsummaryhtml .= '</table>';

$pdf->writeHTML($tblsummaryhtml, true, false, false, false, '');
$pdf->Ln(3);
$tblsummaryfinalhtml = '';
$tblsummaryfinalhtml .= '<table cellpadding="6" style="font-size:14px">';
$tblsummaryfinalhtml .= '
<tr>
    <td align="right" width="75%"><strong>' . _l('estimate_subtotal') . '</strong></td>
    <td align="right" width="25%">'.app_format_money($basic_estimate['final_estimate']['subtotal'], $estimate->currency_name).'</td>
</tr>';
$tblsummaryfinalhtml .= '
<tr>
    <td align="right" width="75%"><strong>' . _l('tax') . '</strong></td>
    <td align="right" width="25%">'.app_format_money($basic_estimate['final_estimate']['tax'], $estimate->currency_name).'</td>
</tr>';
$tblsummaryfinalhtml .= '
<tr>
    <td align="right" width="75%"><strong>' . _l('estimate_total') . '</strong></td>
    <td align="right" width="25%">'.app_format_money($basic_estimate['final_estimate']['amount'], $estimate->currency_name).'</td>
</tr>';
$tblsummaryfinalhtml .= '</table>';
$pdf->writeHTML($tblsummaryfinalhtml, true, false, false, false, '');

if(!empty($summary)) {
    foreach ($summary as $akey => $avalue) {
        $tblannexurehtml = '';
        $tblannexurehtml .= '<h3 style="text-align:center; ">'.$avalue['name'].'</h3>';
        $tblannexurehtml .= '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5">';
        $tblannexurehtml .= '
            <thead>
              <tr height="30" bgcolor="#323a45" style="color:#ffffff; font-size:13px;">
                 <th width="5%;" align="center">'._l('the_number_sign').'</th>
                 <th width="17%" align="left">'._l('sales_item').'</th>
                 <th width="22%" align="left">'._l('estimate_table_item_description').'</th>
                 <th width="6%" align="right">'._l('estimate_table_quantity_heading').'</th>
                 <th width="15%" align="right">'._l('estimate_table_rate_heading').'</th>
                 <th width="15%" align="right">'._l('estimate_table_tax_heading').'</th>
                 <th width="20%" align="right">'._l('estimate_table_amount_heading').'</th>
              </tr>
            </thead>';
        $tblannexurehtml .= '<tbody>';
        $estimate_items = $estimate->items;
        $inv = 1;
        foreach ($estimate_items as $item) {
            if($item['annexure'] == $avalue['annexure']) {
                if (!is_numeric($item['qty'])) {
                    $item['qty'] = 1;
                }
                $amount = $item['rate'] * $item['qty'];
                $total_tax = get_estimate_annexurewise_tax($estimate->id, $avalue['annexure'], $item['id']);
                $tblannexurehtml .= '
                <tr style="font-size:12px;">
                    <td width="5%;" align="center">'.$inv.'</td>
                    <td width="17%" align="left;"><span style="font-size:11px;"><strong>'.clear_textarea_breaks($item['description']).'</strong></span></td>
                    <td width="22%" align="left"><span style="color:#424242;">'.clear_textarea_breaks($item['long_description']).'</span></td>
                    <td width="6%" align="right">'.$item['qty'].'</td>
                    <td width="15%" align="right">'.app_format_money($item['rate'], $estimate->currency_name).'</td>
                    <td width="15%" align="right">'.app_format_money($total_tax, $estimate->currency_name).'</td>
                    <td width="20%" align="right">'.app_format_money($amount, $estimate->currency_name).'</td>
                </tr>';
                $inv++;
            }
        }
        $tblannexurehtml .= '</tbody>';
        $tblannexurehtml .= '</table>';

        $pdf->AddPage();
        $pdf->writeHTML($tblannexurehtml, true, false, false, false, '');
        $pdf->Ln(3);
        $tblannexurefinalhtml = '';
        $tblannexurefinalhtml .= '<table cellpadding="6" style="font-size:14px">';
        $tblannexurefinalhtml .= '
        <tr>
            <td align="right" width="75%"><strong>' . _l('estimate_subtotal') . '</strong></td>
            <td align="right" width="25%">'.app_format_money($avalue['subtotal'], $estimate->currency_name).'</td>
        </tr>';
        $tblannexurefinalhtml .= '
        <tr>
            <td align="right" width="75%"><strong>' . _l('tax') . '</strong></td>
            <td align="right" width="25%">'.app_format_money($avalue['tax'], $estimate->currency_name).'</td>
        </tr>';
        $tblannexurefinalhtml .= '
        <tr>
            <td align="right" width="75%"><strong>' . _l('estimate_total') . '</strong></td>
            <td align="right" width="25%">'.app_format_money($avalue['amount'], $estimate->currency_name).'</td>
        </tr>';
        $tblannexurefinalhtml .= '</table>';
        $pdf->writeHTML($tblannexurefinalhtml, true, false, false, false, '');
    }
}

$pdf->Ln(8);
$tbltotal = '';
$tbltotal .= '<table cellpadding="6" style="font-size:' . ($font_size + 4) . 'px">';
$tbltotal .= '
<tr>
    <td align="right" width="85%"><strong>' . _l('estimate_subtotal') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($basic_estimate['final_estimate']['subtotal'], $estimate->currency_name) . '</td>
</tr>';

if (is_sale_discount_applied($estimate)) {
    $tbltotal .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('estimate_discount');
    if (is_sale_discount($estimate, 'percent')) {
        $tbltotal .= ' (' . app_format_number($estimate->discount_percent, true) . '%)';
    }
    $tbltotal .= '</strong>';
    $tbltotal .= '</td>';
    $tbltotal .= '<td align="right" width="15%">-' . app_format_money($estimate->discount_total, $estimate->currency_name) . '</td>
    </tr>';
}

// foreach ($items->taxes() as $tax) {
//     $tbltotal .= '<tr>
//     <td align="right" width="85%"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($tax['total_tax'], $estimate->currency_name) . '</td>
// </tr>';
// }

$tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . _l('tax') . '</strong></td>
    <td align="right" width="15%">'.app_format_money($basic_estimate['final_estimate']['tax'], $estimate->currency_name).'</td>
</tr>';

if ((int)$estimate->adjustment != 0) {
    $tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . _l('estimate_adjustment') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($estimate->adjustment, $estimate->currency_name) . '</td>
</tr>';
}

$tbltotal .= '
<tr style="background-color:#f0f0f0;">
    <td align="right" width="85%"><strong>' . _l('estimate_total') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($basic_estimate['final_estimate']['amount'], $estimate->currency_name) . '</td>
</tr>';

$tbltotal .= '</table>';

// $pdf->writeHTML($tbltotal, true, false, false, false, '');

if (get_option('total_to_words_enabled') == 1) {
    // Set the font bold
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->writeHTMLCell('', '', '', '', _l('num_word') . ': ' . $CI->numberword->convert($estimate->total, $estimate->currency_name), 0, 1, false, true, 'C', true);
    // Set the font again to normal like the rest of the pdf
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(4);
}

if (!empty($estimate->clientnote)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('estimate_note'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $estimate->clientnote, 0, 1, false, true, 'L', true);
}

if (!empty($estimate->terms)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('terms_and_conditions') . ":", 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $estimate->terms, 0, 1, false, true, 'L', true);
}
