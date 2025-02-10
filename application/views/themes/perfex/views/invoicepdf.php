<?php

defined('BASEPATH') or exit('No direct script access allowed');

$dimensions = $pdf->getPageDimensions();

$info_right_column = '';
$info_left_column  = '';

$info_right_column .= '<span style="font-weight:bold;font-size:27px;">' . _l('invoice_pdf_heading') . '</span><br />';
$info_right_column .= '<b style="color:#4e4e4e;"># ' . $invoice_number . '</b>';

if (get_option('show_status_on_pdf_ei') == 1) {
    $info_right_column .= '<br /><span style="color:rgb(' . invoice_status_color_pdf($status) . ');text-transform:uppercase;">' . format_invoice_status($status, '', false) . '</span>';
}

if (
    $status != Invoices_model::STATUS_PAID && $status != Invoices_model::STATUS_CANCELLED && get_option('show_pay_link_to_invoice_pdf') == 1
    && found_invoice_mode($payment_modes, $invoice->id, false)
) {
    $info_right_column .= ' - <a style="color:#84c529;text-decoration:none;text-transform:uppercase;" href="' . site_url('invoice/' . $invoice->id . '/' . $invoice->hash) . '"><1b>' . _l('view_invoice_pdf_link_pay') . '</1b></a>';
}

// Add logo
$info_left_column .= pdf_logo_url();

// Write top left logo and right column info/text
pdf_multi_row($info_left_column, $info_right_column, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

$pdf->ln(10);

$organization_info = '<div style="color:#424242;">';

$organization_info .= format_organization_info();

$hsn_sac_value = '';
$hsn_sac_code = '';
if ($invoice->hsn_sac) {
    $hsn_sac_value = get_hsn_sac_name_by_id($invoice->hsn_sac);
    $organization_info .= '<br />'._l('hsn_sac') . ': ' . $hsn_sac_value . '<br />';
    if(!empty($hsn_sac_value)) {
        $parts = explode(' - ', $hsn_sac_value);
        $hsn_sac_code = $parts[0];
    }
}

$organization_info .= '</div>';

// Bill to
$invoice_info = '<b>' . _l('invoice_bill_to') . ':</b>';
$invoice_info .= '<div style="color:#424242;">';
$invoice_info .= format_customer_info($invoice, 'invoice', 'billing');
$invoice_info .= '</div>';

// ship to to
if ($invoice->include_shipping == 1 && $invoice->show_shipping_on_invoice == 1) {
    $invoice_info .= '<br /><b>' . _l('ship_to') . ':</b>';
    $invoice_info .= '<div style="color:#424242;">';
    $invoice_info .= format_customer_info($invoice, 'invoice', 'shipping');
    $invoice_info .= '</div>';
}

$invoice_info .= '<br />' . _l('invoice_data_date') . ' ' . _d($invoice->date) . '<br />';

$invoice_info = hooks()->apply_filters('invoice_pdf_header_after_date', $invoice_info, $invoice);

if (!empty($invoice->duedate)) {
    $invoice_info .= _l('invoice_data_duedate') . ' ' . _d($invoice->duedate) . '<br />';
    $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_due_date', $invoice_info, $invoice);
}

if ($invoice->sale_agent && get_option('show_sale_agent_on_invoices') == 1) {
    $invoice_info .= _l('sale_agent_string') . ': ' . get_staff_full_name($invoice->sale_agent) . '<br />';
    $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_sale_agent', $invoice_info, $invoice);
}

if ($invoice->project_id && get_option('show_project_on_invoice') == 1) {
    $invoice_info .= _l('project') . ': ' . get_project_name_by_id($invoice->project_id) . '<br />';
    $invoice_info = hooks()->apply_filters('invoice_pdf_header_after_project_name', $invoice_info, $invoice);
}
// if ($invoice->hsn_sac) {
//     $invoice_info .= _l('hsn_sac') . ': ' . get_hsn_sac_name_by_id($invoice->hsn_sac) . '<br />';
// }
if ($invoice->deal_slip_no) {
    $invoice_info .= _l('deal_slip_no') . ': ' . $invoice->deal_slip_no . '<br />';
}
$invoice_info = hooks()->apply_filters('invoice_pdf_header_before_custom_fields', $invoice_info, $invoice);

foreach ($pdf_custom_fields as $field) {
    $value = get_custom_field_value($invoice->id, $field['id'], 'invoice');
    if ($value == '') {
        continue;
    }
    $invoice_info .= $field['name'] . ': ' . $value . '<br />';
}

$invoice_info      = hooks()->apply_filters('invoice_pdf_header_after_custom_fields', $invoice_info, $invoice);
$organization_info = hooks()->apply_filters('invoicepdf_organization_info', $organization_info, $invoice);
$invoice_info      = hooks()->apply_filters('invoice_pdf_info', $invoice_info, $invoice);

$left_info  = $swap == '1' ? $invoice_info : $organization_info;
$right_info = $swap == '1' ? $organization_info : $invoice_info;

pdf_multi_row($left_info, $right_info, $pdf, ($dimensions['wk'] / 2) - $dimensions['lm']);

// The Table
$pdf->Ln(hooks()->apply_filters('pdf_info_and_table_separator', 6));

// The items table
// $items = get_items_table_data($invoice, 'invoice', 'pdf');
// $tblhtml = $items->table();

$tblfinvoicehtml = '';
$tblfinvoicehtml .= '<h3 style="text-align:center; ">Final invoice</h3>';
$tblfinvoicehtml .= '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5">';
$tblfinvoicehtml .= '
<thead>
  <tr height="30" bgcolor="#323a45" style="color:#ffffff; font-size:12px;">
     <th width="5%;" align="center">' . _l('the_number_sign') . '</th>
     <th width="11%" align="left">' . _l('budget_head') . '</th>
     <th width="28%" align="left">' . _l('description_of_services') . '</th>
     <th width="10%" align="left">HSN/SAC</th>
     <th width="13%" align="right">' . _l('invoice_table_rate_heading') . '</th>
     <th width="11%" align="right">' . _l('invoice_table_tax_heading') . '</th>
     <th width="18%" align="right">' . _l('invoice_table_amount_heading') . '</th>
  </tr>
</thead>';
$tblfinvoicehtml .= '<tbody>';
$tblfinvoicehtml .= '
<tr style="font-size:12px;">
    <td width="5%;" align="center">1</td>
    <td width="11%" align="left;"><span style="font-size:12px;"><strong>' . $basic_invoice['final_invoice']['name'] . '</strong></span></td>
    <td width="28%" align="left">' . $basic_invoice['final_invoice']['description'] . '</td>
    <td width="10%" align="left">' . $hsn_sac_code . '</td>
    <td width="13%" align="right">' . app_format_money($basic_invoice['final_invoice']['subtotal'], $invoice->currency_name) . '</td>
    <td width="11%" align="right">' . app_format_money($basic_invoice['final_invoice']['tax'], $invoice->currency_name) . '</td>
    <td width="18%" align="right">' . app_format_money($basic_invoice['final_invoice']['amount'], $invoice->currency_name) . '</td>
</tr>';
$tblfinvoicehtml .= '</tbody>';
$tblfinvoicehtml .= '</table>';

$pdf->writeHTML($tblfinvoicehtml, true, false, false, false, '');

$amount_to_word = amount_to_word($basic_invoice['final_invoice']['amount']);
$tbltotalinvhtml = '';
$tbltotalinvhtml .= '<table width="100%" cellspacing="0" cellpadding="0">';
$tbltotalinvhtml .= '
<thead>
  <tr height="30" style="font-size:14px;">
     <th width="100%;" align="left">
        <strong>'.$amount_to_word.'.</strong>
     </th>
  </tr>
</thead>';
$tbltotalinvhtml .= '</table>';

$pdf->writeHTML($tbltotalinvhtml, true, false, false, false, '');

$bank_details = get_bank_details($invoice->clientid);
$tblbanksignhtml = '';
$tblbanksignhtml .= '<table width="100%" cellspacing="0" cellpadding="0">';
$tblbanksignhtml .= '
<tbody>
  <tr style="font-size:14px;">
     <td width="50%;" align="left">
        <strong>' . _l('bank_detail') . '</strong>
        <br>
        '.$bank_details.'
     </td>
     <td width="50%;" align="right">
        <strong>For BASILIUS INTERNATIONAL LLP</strong>
        <br /><br /><br /><br /><br />
        _________________________
        <br />
        Authorised Signatory
     </td>
  </tr>
</thead>';
$tblbanksignhtml .= '</table>';

$pdf->writeHTML($tblbanksignhtml, true, false, false, false, '');
$pdf->AddPage();

$tblbudgetsummaryhtml = '';
$tblbudgetsummaryhtml .= '<h3 style="text-align:center; ">Budget summary</h3>';
$tblbudgetsummaryhtml .= '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="3">';
$tblbudgetsummaryhtml .= '
<thead>
  <tr height="30" bgcolor="#323a45" style="color:#ffffff; font-size:12px;">
     <th width="5%;" align="center">' . _l('the_number_sign') . '</th>
     <th width="12%" align="left">' . _l('budget_head') . '</th>
     <th width="16%" align="right">' . _l('budgeted_amount') . '</th>
     <th width="17%" align="right">' . _l('total_previous_billing') . '</th>
     <th width="17%" align="right">' . _l('total_current_billing_amount') . '</th>
     <th width="16%" align="right">' . _l('total_cumulative_billing') . '</th>
     <th width="17%" align="right">' . _l('balance_available') . '</th>
  </tr>
</thead>';
$tblbudgetsummaryhtml .= '<tbody>';
$budgetsummary = $basic_invoice['budgetsummary'];
foreach ($budgetsummary as $ikey => $ivalue) {
    $tblbudgetsummaryhtml .= '
    <tr style="font-size:12px;">
        <td width="5%;" align="center">' . ($ikey + 1) . '</td>
        <td width="12%" align="left;"><span style="font-size:12px;"><strong>' . $ivalue['name'] . '</strong></span></td>
        <td width="16%" align="right">' . app_format_money($ivalue['budgeted_amount'], $invoice->currency_name) . '</td>
        <td width="17%" align="right">' . app_format_money($ivalue['total_previous_billing'], $invoice->currency_name) . '</td>
        <td width="17%" align="right">' . app_format_money($ivalue['total_current_billing_amount'], $invoice->currency_name) . '</td>
        <td width="16%" align="right">' . app_format_money($ivalue['total_cumulative_billing'], $invoice->currency_name) . '</td>
        <td width="17%" align="right">' . app_format_money($ivalue['balance_available'], $invoice->currency_name) . '</td>
        
    </tr>';
}
$tblbudgetsummaryhtml .= '</tbody>';
$tblbudgetsummaryhtml .= '</table>';

$pdf->writeHTML($tblbudgetsummaryhtml, true, false, false, false, '');

$tblindexahtml = '';
$tblindexahtml .= '<h3 style="text-align:center; ">Index - A</h3>';
$tblindexahtml .= '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="8">';
$tblindexahtml .= '
<thead>
  <tr height="30" bgcolor="#323a45" style="color:#ffffff; font-size:14px;">
     <th width="7%;" align="center">' . _l('the_number_sign') . '</th>
     <th width="38%" align="left">' . _l('budget_head') . '</th>
     <th width="20%" align="right">' . _l('invoice_table_rate_heading') . '</th>
     <th width="15%" align="right">' . _l('invoice_table_tax_heading') . '</th>
     <th width="20%" align="right">' . _l('invoice_table_amount_heading') . '</th>
  </tr>
</thead>';
$tblindexahtml .= '<tbody>';
$indexa = $basic_invoice['indexa'];
foreach ($indexa as $ikey => $ivalue) {
    $tblindexahtml .= '
    <tr style="font-size:13px;">
        <td width="7%;" align="center">' . ($ikey + 1) . '</td>
        <td width="38%" align="left;"><span style="font-size:13px;"><strong>' . $ivalue['name'] . '</strong></span></td>
        <td width="20%" align="right">' . app_format_money($ivalue['subtotal'], $invoice->currency_name) . '</td>
        <td width="15%" align="right">' . app_format_money($ivalue['tax'], $invoice->currency_name) . '</td>
        <td width="20%" align="right">' . app_format_money($ivalue['amount'], $invoice->currency_name) . '</td>
    </tr>';
}
$tblindexahtml .= '</tbody>';
$tblindexahtml .= '</table>';

$pdf->writeHTML($tblindexahtml, true, false, false, false, '');
$pdf->Ln(3);
$tblindexafinalhtml = '';
$tblindexafinalhtml .= '<table cellpadding="6" style="font-size:14px">';
$tblindexafinalhtml .= '
<tr>
    <td align="right" width="85%"><strong>' . _l('invoice_subtotal') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($basic_invoice['final_invoice']['subtotal'], $invoice->currency_name) . '</td>
</tr>';
$tblindexafinalhtml .= '
<tr>
    <td align="right" width="85%"><strong>' . _l('tax') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($basic_invoice['final_invoice']['tax'], $invoice->currency_name) . '</td>
</tr>';
$tblindexafinalhtml .= '
<tr>
    <td align="right" width="85%"><strong>' . _l('invoice_total') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($basic_invoice['final_invoice']['amount'], $invoice->currency_name) . '</td>
</tr>';
$tblindexafinalhtml .= '</table>';
$pdf->writeHTML($tblindexafinalhtml, true, false, false, false, '');

if (!empty($indexa)) {
    foreach ($indexa as $akey => $avalue) {
        $tblannexurehtml = '';
        $tblannexurehtml .= '<h3 style="text-align:center; ">' . $avalue['name'] . '</h3>';
        $tblannexurehtml .= '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5">';
        $tblannexurehtml .= '
            <thead>
              <tr height="30" bgcolor="#323a45" style="color:#ffffff; font-size:13px;">
                 <th width="5%;" align="center">' . _l('the_number_sign') . '</th>
                 <th width="13%" align="left">' . _l('budget_head') . '</th>
                 <th width="16%" align="left">' . _l('description_of_services') . '</th>
                 <th width="16%" align="left">' . _l('vendor') . '</th>
                 <th width="12%" align="left">' . _l('invoice_no') . '</th>
                 <th width="12%" align="right">' . _l('invoice_table_rate_heading') . '</th>
                 <th width="11%" align="right">' . _l('invoice_table_tax_heading') . '</th>
                 <th width="15%" align="right">' . _l('invoice_table_amount_heading') . '</th>
              </tr>
            </thead>';
        $tblannexurehtml .= '<tbody>';
        $invoice_items = $invoice->items;
        $inv = 1;
        $invoice_tax = get_annexurewise_tax($invoice->id);
        foreach ($invoice_items as $item) {
            if ($item['annexure'] == $avalue['annexure']) {
                if (!is_numeric($item['qty'])) {
                    $item['qty'] = 1;
                }
                $amount = $item['rate'] * $item['qty'];
                $total_tax = 0;
                $annexure = $item['annexure'];
                $itemid = $item['id'];
                if(!empty($invoice_tax)) {
                    $item_tax_array = array_filter($invoice_tax, function ($item) use ($annexure, $itemid) {
                        return ($item['annexure'] == $annexure && $item['item_id'] == $itemid);
                    });
                    $item_tax_array = !empty($item_tax_array) ? array_values($item_tax_array) : array();
                    $total_tax = !empty($item_tax_array) ? $item_tax_array[0]['total_tax'] : 0;
                }
                $vendor_name = '';
                $invoice_no = '';
                if (!empty($item['po_id'])) {
                    $pur_orders = get_pur_orders($item['po_id']);
                    $vendor = get_vendor_details($pur_orders->vendor);
                    $vendor_name = $vendor->company;
                    $invoice_no = $pur_orders->pur_order_number;
                }
                if (!empty($item['wo_id'])) {
                    $wo_orders = get_wo_orders($item['wo_id']);
                    $vendor = get_vendor_details($wo_orders->vendor);
                    $vendor_name = $vendor->company;
                    $invoice_no = $wo_orders->wo_order_number;
                }
                if (!empty($item['vbt_id'])) {
                    $pur_invoices = get_pur_invoices($item['vbt_id']);
                    $vendor = get_vendor_details($pur_invoices->vendor);
                    $vendor_name = $vendor->company;
                    $invoice_no = $pur_invoices->vendor_invoice_number;
                }
                $tblannexurehtml .= '
                <tr style="font-size:12px;">
                    <td width="5%;" align="center">' . $inv . '</td>
                    <td width="13%" align="left;"><span style="font-size:11px;"><strong>' . clear_textarea_breaks($item['description']) . '</strong></span></td>
                    <td width="16%" align="left"><span style="color:#424242;">' . clear_textarea_breaks($item['long_description']) . '</span></td>
                    <td width="16%" align="left">' . $vendor_name . '</td>
                    <td width="12%" align="left">' . $invoice_no . '</td>
                    <td width="12%" align="right">' . app_format_money($item['rate'], $invoice->currency_name) . '</td>
                    <td width="11%" align="right">' . app_format_money($total_tax, $invoice->currency_name) . '</td>
                    <td width="15%" align="right">' . app_format_money($amount, $invoice->currency_name) . '</td>
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
            <td align="right" width="85%"><strong>' . _l('invoice_subtotal') . '</strong></td>
            <td align="right" width="15%">' . app_format_money($avalue['subtotal'], $invoice->currency_name) . '</td>
        </tr>';
        $tblannexurefinalhtml .= '
        <tr>
            <td align="right" width="85%"><strong>' . _l('tax') . '</strong></td>
            <td align="right" width="15%">' . app_format_money($avalue['tax'], $invoice->currency_name) . '</td>
        </tr>';
        $tblannexurefinalhtml .= '
        <tr>
            <td align="right" width="85%"><strong>' . _l('invoice_total') . '</strong></td>
            <td align="right" width="15%">' . app_format_money($avalue['amount'], $invoice->currency_name) . '</td>
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
    <td align="right" width="85%"><strong>' . _l('invoice_subtotal') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($basic_invoice['final_invoice']['subtotal'], $invoice->currency_name) . '</td>
</tr>';

if (is_sale_discount_applied($invoice)) {
    $tbltotal .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('invoice_discount');
    if (is_sale_discount($invoice, 'percent')) {
        $tbltotal .= ' (' . app_format_number($invoice->discount_percent, true) . '%)';
    }
    $tbltotal .= '</strong>';
    $tbltotal .= '</td>';
    $tbltotal .= '<td align="right" width="15%">-' . app_format_money($invoice->discount_total, $invoice->currency_name) . '</td>
    </tr>';
}

// foreach ($items->taxes() as $tax) {
//     $tbltotal .= '<tr>
//     <td align="right" width="85%"><strong>' . $tax['taxname'] . ' (' . app_format_number($tax['taxrate']) . '%)' . '</strong></td>
//     <td align="right" width="15%">' . app_format_money($tax['total_tax'], $invoice->currency_name) . '</td>
// </tr>';
// }

$tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . _l('tax') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($basic_invoice['final_invoice']['tax'], $invoice->currency_name) . '</td>
</tr>';

if ((int) $invoice->adjustment != 0) {
    $tbltotal .= '<tr>
    <td align="right" width="85%"><strong>' . _l('invoice_adjustment') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($invoice->adjustment, $invoice->currency_name) . '</td>
</tr>';
}

$tbltotal .= '
<tr style="background-color:#f0f0f0;">
    <td align="right" width="85%"><strong>' . _l('invoice_total') . '</strong></td>
    <td align="right" width="15%">' . app_format_money($basic_invoice['final_invoice']['amount'], $invoice->currency_name) . '</td>
</tr>';

if (count($invoice->payments) > 0 && get_option('show_total_paid_on_invoice') == 1) {
    $tbltotal .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('invoice_total_paid') . '</strong></td>
        <td align="right" width="15%">-' . app_format_money(sum_from_table(db_prefix() . 'invoicepaymentrecords', [
        'field' => 'amount',
        'where' => [
            'invoiceid' => $invoice->id,
        ],
    ]), $invoice->currency_name) . '</td>
    </tr>';
}

if (get_option('show_credits_applied_on_invoice') == 1 && $credits_applied = total_credits_applied_to_invoice($invoice->id)) {
    $tbltotal .= '
    <tr>
        <td align="right" width="85%"><strong>' . _l('applied_credits') . '</strong></td>
        <td align="right" width="15%">-' . app_format_money($credits_applied, $invoice->currency_name) . '</td>
    </tr>';
}

if (get_option('show_amount_due_on_invoice') == 1 && $invoice->status != Invoices_model::STATUS_CANCELLED) {
    $tbltotal .= '<tr style="background-color:#f0f0f0;">
       <td align="right" width="85%"><strong>' . _l('invoice_amount_due') . '</strong></td>
       <td align="right" width="15%">' . app_format_money($invoice->total_left_to_pay, $invoice->currency_name) . '</td>
   </tr>';
}

$tbltotal .= '</table>';
// $pdf->writeHTML($tbltotal, true, false, false, false, '');

if (get_option('total_to_words_enabled') == 1) {
    // Set the font bold
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->writeHTMLCell('', '', '', '', _l('num_word') . ': ' . $CI->numberword->convert($invoice->total, $invoice->currency_name), 0, 1, false, true, 'C', true);
    // Set the font again to normal like the rest of the pdf
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(4);
}

if (count($invoice->payments) > 0 && get_option('show_transactions_on_invoice_pdf') == 1) {
    $pdf->Ln(4);
    $border = 'border-bottom-color:#000000;border-bottom-width:1px;border-bottom-style:solid; 1px solid black;';
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('invoice_received_payments') . ':', 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(4);
    $tblhtml = '<table width="100%" bgcolor="#fff" cellspacing="0" cellpadding="5" border="0">
        <tr height="20"  style="color:#000;border:1px solid #000;">
        <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_number_heading') . '</th>
        <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_mode_heading') . '</th>
        <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_date_heading') . '</th>
        <th width="25%;" style="' . $border . '">' . _l('invoice_payments_table_amount_heading') . '</th>
    </tr>';
    $tblhtml .= '<tbody>';
    foreach ($invoice->payments as $payment) {
        $payment_name = $payment['name'];
        if (!empty($payment['paymentmethod'])) {
            $payment_name .= ' - ' . $payment['paymentmethod'];
        }
        $tblhtml .= '
            <tr>
            <td>' . $payment['paymentid'] . '</td>
            <td>' . $payment_name . '</td>
            <td>' . _d($payment['date']) . '</td>
            <td>' . app_format_money($payment['amount'], $invoice->currency_name) . '</td>
            </tr>
        ';
    }
    $tblhtml .= '</tbody>';
    $tblhtml .= '</table>';
    $pdf->writeHTML($tblhtml, true, false, false, false, '');
}

if (found_invoice_mode($payment_modes, $invoice->id, true, true)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('invoice_html_offline_payment') . ':', 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);

    foreach ($payment_modes as $mode) {
        if (is_numeric($mode['id'])) {
            if (!is_payment_mode_allowed_for_invoice($mode['id'], $invoice->id)) {
                continue;
            }
        }
        if (isset($mode['show_on_pdf']) && $mode['show_on_pdf'] == 1) {
            $pdf->Ln(1);
            $pdf->Cell(0, 0, $mode['name'], 0, 1, 'L', 0, '', 0);
            $pdf->Ln(2);
            $pdf->writeHTMLCell('', '', '', '', $mode['description'], 0, 1, false, true, 'L', true);
        }
    }
}

if (!empty($invoice->clientnote)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('invoice_note'), 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $invoice->clientnote, 0, 1, false, true, 'L', true);
}

if (!empty($invoice->terms)) {
    $pdf->Ln(4);
    $pdf->SetFont($font_name, 'B', $font_size);
    $pdf->Cell(0, 0, _l('terms_and_conditions') . ':', 0, 1, 'L', 0, '', 0);
    $pdf->SetFont($font_name, '', $font_size);
    $pdf->Ln(2);
    $pdf->writeHTMLCell('', '', '', '', $invoice->terms, 0, 1, false, true, 'L', true);
}
