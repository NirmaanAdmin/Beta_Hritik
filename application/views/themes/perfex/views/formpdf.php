<?php
defined('BASEPATH') or exit('No direct script access allowed');
$upload_path = FCPATH . 'uploads/';
$formhtml = '';

$formhtml .= '<table width="100%" cellspacing="0" cellpadding="5" border="1">';
$formhtml .= '<tbody>';
$formhtml .= '
<tr>
    <td colspan="11" width="100%;" align="center" style="font-weight:bold; font-size: 16px;">
        BGJ-Project
    </td>
</tr>';
$formhtml .= '
<tr>
    <td colspan="11" width="100%;" align="center" style="font-weight:bold; font-size: 16px;">
        Quality Observation Report
    </td>
</tr>';

$formhtml .= '<tr>';
$formhtml .= '<th style="text-align: center; font-weight: bold;" width="3%">Sr. no.</th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="7%">Date</th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="5%">Floor</th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="7%">Location</th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="15%">Observation</th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="7%">Category</th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="14%">Photograph</th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="14%">Compliance Photograph </th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="14%">Compliance Details</th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="5%">Status</th>';

$formhtml .= '<th style="text-align: center; font-weight: bold;" width="9%">Remarks</th>';

$formhtml .= '</tr>';


$sr = 1;
foreach ($qcr_data as $data) {
    $formhtml .= '<tr>';
    // Sr no.
    $formhtml .= '<td style="text-align:center;">' . $sr . '</td>';

    // Date (format if needed)
    $date = !empty($data['date']) ? date('d M, Y', strtotime($data['date'])) : '';
    $formhtml .= '<td style="text-align:center;">' . $date . '</td>';

    // Floor, Location, Observation, Category
    $formhtml .= '<td style="text-align:center;">' . htmlspecialchars($data['floor']) . '</td>';
    $formhtml .= '<td style="text-align:center;">' . htmlspecialchars($data['location']) . '</td>';
    $formhtml .= '<td style="text-align:center;">' . htmlspecialchars($data['observation']) . '</td>';
    $formhtml .= '<td style="text-align:center;">' . htmlspecialchars(get_qcr_category_by_id($data['category'])) . '</td>';

    // Photograph
    $formhtml .= '<td style="text-align:center;">';
    
    if (!empty($data['photograph'])) {
        $image_path = $upload_path . 'form_attachments/qcr_attachments/' 
        . $data['form_id'] . '/' 
        . $data['id'] . '/' 
        . $data['photograph'];
        
        $formhtml .= '<img src="' . $image_path . '" style="width:100px; height:150px;">';
    }
    
    $formhtml .= '</td>';

    // Compliance Photograph
    $formhtml .= '<td style="text-align:center;">';
    if (!empty($data['compliance_photograph'])) {
        // $url = base_url('uploads/compliance_photograph/'.$data['form_id'].'/'.$data['id'].'/' .$data['compliance_photograph']);
        $url = $upload_path . 'form_attachments/compliance_photograph/' 
        . $data['form_id'] . '/' 
        . $data['id'] . '/' 
        . $data['compliance_photograph'];
        $formhtml .= '<img src="' . $url . '" width="100">';
    }
    $formhtml .= '</td>';

    // Compliance details, status, remarks
    $formhtml .= '<td style="text-align:center;">' . htmlspecialchars($data['compliance_detail']) . '</td>';
    $formhtml .= '<td style="text-align:center;">' . htmlspecialchars(get_qcr_status_by_id($data['status'])) . '</td>';
    $formhtml .= '<td style="text-align:center;">' . htmlspecialchars($data['remarks']) . '</td>';

    $sr++;
    $formhtml .= '</tr>';
}


$formhtml .= '</tbody>';
$formhtml .= '</table>';

$pdf->SetPageOrientation('L', true, true);
$pdf->writeHTML($formhtml, true, false, true, false, '');
