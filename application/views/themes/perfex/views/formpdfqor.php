<?php


defined('BASEPATH') or exit('No direct script access allowed');

$upload_path = FCPATH . 'uploads/';
$formhtml = '';

$company_logo = get_option('company_logo_dark');
$logo = '';
if (!empty($company_logo)) {
    $logo_path = FCPATH . 'uploads/company/' . $company_logo;
    if (file_exists($logo_path)) {
        $image_data = file_get_contents($logo_path);
        $base64 = 'data:image/png;base64,' . base64_encode($image_data);
        $logo = '<div class="logo">
                <img src="' . $base64 . '" width="130" height="100">
            </div>';
    }
}
if (!empty($logo)) {
    $formhtml .= '<div style="text-align: center; margin-bottom: 20px;">';
    $formhtml .= $logo;
    $formhtml .= '</div>';
}

$formhtml .= '<table width="100%" cellspacing="0" cellpadding="5" border="1">';
$formhtml .= '<tbody>';
$formhtml .= '
<tr>
    <td colspan="11" width="100%" align="center" style="font-weight:bold; font-size: 16px;">
        Quality Observation Report
    </td>
</tr>';

$formhtml .= '<tr>';
$formhtml .= '<th colspan="1"><span style="font-weight: bold;">Raised by:</span> </th>';
$formhtml .= '<th colspan="2"><span style="font-weight: bold;">' . (isset($qor_data)  ? $qor_data->raised_by : '')  . '</span></th>';
$formhtml .= '<th colspan="2"><span style="font-weight: bold;">Issue Date: </span> </th>';
$issue_date = (isset($qor_data)  && $qor_data->issue_date != '' ?  date('d M, Y', strtotime($qor_data->issue_date)) : ' ');
$formhtml .= '<th colspan="2"><span style="font-weight: bold;">' . $issue_date  . '</span></th>';
$formhtml .= '<th colspan="2"><span style="font-weight: bold;">Observation No:</span> </th>';
$formhtml .= '<th colspan="2"><span style="font-weight: bold;">' . (isset($qor_data)  ? $qor_data->observation_no : '') . '</span></th>';
$formhtml .= '</tr>';

$formhtml .= '<tr>';
$formhtml .= '<th colspan="2"><span style="font-weight: bold;">Material or Works Involved: </span> </th>';
$formhtml .= '<th colspan="3">' . (isset($qor_data)  ? $qor_data->material_or_works_involved : '') . '</th>';
$formhtml .= '<th colspan="3"><span style="font-weight: bold;">Supplier/Contractor in Charge: </span> </th>';
if ($qor_data->supplier_contractor_in_charge > 0) {
    $vendor_name = get_vendor_list_for_forms($qor_data->supplier_contractor_in_charge);
    $vendor_name = $vendor_name[0]['company'];
} else {
    $vendor_name = '';
}
$formhtml .= '<th colspan="3">' . (isset($qor_data)   ? $vendor_name : '') . '</th>';
$formhtml .= '</tr>';

$formhtml .= '<tr>';
$formhtml .= '<th colspan="2"><span style="font-weight: bold;">Specification/Drawing Reference:</span> </th>';
$formhtml .= '<th colspan="3">' . (isset($qor_data)  ? $qor_data->specification_drawing_reference : '') . '</th>';
$formhtml .= '<th colspan="3"><span style="font-weight: bold;">Procedure or ITP Reference:</span> </th>';
$formhtml .= '<th colspan="3">' . (isset($qor_data)  ? $qor_data->procedure_or_itp_reference : '') . '</th>';
$formhtml .= '</tr>';

$formhtml .= '<tr>';
$formhtml .= '<th colspan="2"><span style="font-weight: bold;">Location:</span> </th>';
$formhtml .= '<th colspan="9"></th>';
$formhtml .= '</tr>';

// Observation Description with Attachment
$formhtml .= '<tr>';
$formhtml .= '<th rowspan="2" colspan="2"><span style="font-weight: bold;">Observation Description</span></th>';
$formhtml .= '<td colspan="7" rowspan="2">' . (isset($qor_data)  ? $qor_data->observation_description : '') . '</td>';
$formhtml .= '<td colspan="2" style="border-bottom: 1px solid #000"><span style="font-weight: bold;">Attachment</span></td>';
$formhtml .= '</tr>';
$formhtml .= '<tr>';
$formhtml .= '<td colspan="2">'.count($attachments).'  attached</td>';
$formhtml .= '</tr>';

// Design Consultant Recommendation
$ref_date1 = ((isset($qor_data) && $qor_data->ref_date1 != '0000-00-00' && $qor_data->ref_date1 != '' )  ?  date('d M, Y', strtotime($qor_data->ref_date1)) : ' ');
$formhtml .= '<tr>';
$formhtml .= '<th rowspan="2" colspan="2"><span style="font-weight: bold;">Design Consultant Recommendation</span></th>';
$formhtml .= '<td colspan="7" rowspan="2">' . (isset($qor_data)  ? $qor_data->design_consultant_recommendation : '') . '</td>';
$formhtml .= '<td colspan="2" style="border-bottom: 1px solid #000"><span style="font-weight: bold;">Ref. & Date</span></td>';
$formhtml .= '</tr>';
$formhtml .= '<tr>';
$formhtml .= '<td colspan="2">' . $ref_date1  . '</td>';
$formhtml .= '</tr>';

// Client Instruction
$ref_date2 = (isset($qor_data) && $qor_data->ref_date2 != '' && $qor_data->ref_date2 != '0000-00-00'  ? date('d M, Y', strtotime($qor_data->ref_date2))  : '');
$formhtml .= '<tr>';
$formhtml .= '<th rowspan="2" colspan="2"><span style="font-weight: bold;">Client Instruction</span></th>';
$formhtml .= '<td colspan="7" rowspan="2"> ' . (isset($qor_data)  ? $qor_data->client_instruction : '') . '</td>';
$formhtml .= '<td colspan="2" style="border-bottom: 1px solid #000"><span style="font-weight: bold;">Ref. & Date</span></td>';
$formhtml .= '</tr>';
$formhtml .= '<tr>';
$formhtml .= '<td colspan="2">' . $ref_date2 . '</td>';
$formhtml .= '</tr>';

// Supplier/Contractor's Proposed Corrective Action
$formhtml .= '<tr>';
$formhtml .= '<th rowspan="3" colspan="2"><span style="font-weight: bold;">Supplier/Contractor\'s Proposed Corrective Action</span></th>';
$formhtml .= '<td colspan="9" style="border-bottom: 1px solid #000"><span style="font-weight: bold;">Immediate Action: </span><br> ' . (isset($qor_data)  ? $qor_data->suppliers_proposed_corrective_action1 : '') . '</td>';
$formhtml .= '</tr>';
$formhtml .= '<tr>';
$formhtml .= '<td colspan="9" style="border-bottom: 1px solid #000"><span style="font-weight: bold;">Measure to prevent recurrence:</span><br> ' . (isset($qor_data)  ? $qor_data->suppliers_proposed_corrective_action2 : '') . '</td>';
$formhtml .= '</tr>';
$formhtml .= '<tr>';
$proposed_date = (isset($qor_data) && $qor_data->proposed_date != ''  ? date('d M, Y', strtotime($qor_data->proposed_date)) : '');
$formhtml .= '<td colspan="4" style="border-right: 1px solid #000">Date: ' . $proposed_date . '</td>';
$formhtml .= '<td colspan="4" style="border-bottom: 1px solid #000">Signature:</td>';
$formhtml .= '</tr>';
$unchecked_img_path = base_url('assets/images/unchecked.png');
$checked_img_path = base_url('assets/images/checked.png');

$formhtml .= '<tr>';
$formhtml .= '<td colspan="11" >';
$formhtml .= '<div style="display: inline-flex; align-items: center; gap: 30px; white-space: nowrap;">';

// Approved to Proceed
$formhtml .= '<label style="display: inline-flex; align-items: center; gap: 5px; margin: 0;">';
$formhtml .= '<img src="' . (isset($qor_data) && $qor_data->approval === 'proceed' ? $checked_img_path : $unchecked_img_path) . '"';
$formhtml .= ' alt="checkbox" style="width: 15px; height: 15px; vertical-align: middle;">';
$formhtml .= 'Approved to Proceed';
$formhtml .= '</label>                       ';

// Approved with Comments
$formhtml .= '<label style="display: inline-flex; align-items: center; gap: 5px; margin: 0;">';
$formhtml .= '<img src="' . (isset($qor_data) && $qor_data->approval === 'proceed_comments' ? $checked_img_path : $unchecked_img_path) . '"';
$formhtml .= ' alt="checkbox" style="width: 15px; height: 15px; vertical-align: middle;">';
$formhtml .= 'Approved with Comments';
$formhtml .= '</label>                      ';

// Not Approved
$formhtml .= '<label style="display: inline-flex; align-items: center; gap: 5px; margin: 0;">';
$formhtml .= '<img src="' . (isset($qor_data) && $qor_data->approval === 'not_approved' ? $checked_img_path : $unchecked_img_path) . '"';
$formhtml .= ' alt="checkbox" style="width: 15px; height: 15px; vertical-align: middle;">';
$formhtml .= 'Not Approved';
$formhtml .= '</label>';

$formhtml .= '</div>';
$formhtml .= '</td>';
$formhtml .= '</tr>';
$formhtml .= '<tr>';
$formhtml .= '<td colspan="11" style="padding: 10px; border-top: 2px solid #000;">';
$formhtml .= '<div style="font-weight: bold; margin-bottom: 10px;">Comments: ' . (isset($qor_data)  ? $qor_data->staff_comments : '') . '</div>';
$formhtml .= '</td>';
$formhtml .= '</tr>';

$staff_name_date = (isset($qor_data) && $qor_data->staff_name_date != '' &&  $qor_data->staff_name_date != '0000-00-00'  ?  date('d M, Y', strtotime($qor_data->staff_name_date)) : '');
$formhtml .= '<tr>';
$formhtml .= '<td style="width: 20%;"><span style="font-weight: bold;">Name</span></td>';
if ($qor_data->staff_name > 0) {
    $where = 'staffid = ' . $qor_data->staff_name;
    $get_staff_name = get_staff_list($where);
    $get_staff_name = $get_staff_name[0]['name'];
} else {
    $get_staff_name = '';
}

$formhtml .= '<td style="width: 30%; border-bottom: 1px solid #000;border-top: 1px solid #000;">' . $get_staff_name . ' </td>';
$formhtml .= '<td style="width: 50%; border-left: 1px solid #000;border-top: 1px solid #000;"><span style="font-weight: bold;">Signature & Date :</span> ' . $staff_name_date  . '</td>';
$formhtml .= '</tr>';

$formhtml .= '<tr>';
$formhtml .= '<td style="font-weight: bold; vertical-align: top;">Observation Close-Out</td>';
$formhtml .= '<td colspan="2">';
$formhtml .= '<div style="display: flex; gap: 30px; margin-bottom: 10px;">';
$formhtml .= '<label style="display: flex; align-items: center; gap: 5px;">';
$formhtml .= '<img src="' . (isset($qor_data) && $qor_data->close_out === 'corrective_action' ? $checked_img_path : $unchecked_img_path) . '"';
$formhtml .= ' alt="checkbox" style="width: 15px; height: 15px; vertical-align: middle;">';
$formhtml .= 'Corrective Action Accepted';
$formhtml .= '</label>';
$formhtml .= '<label style="display: flex; align-items: center; gap: 5px;">';
$formhtml .= '<img src="' . (isset($qor_data) && $qor_data->close_out === 'corrective_action_not_accepted' ? $checked_img_path : $unchecked_img_path) . '"';
$formhtml .= ' alt="checkbox" style="width: 15px; height: 15px; vertical-align: middle;">';
$formhtml .= 'Corrective Action Not Accepted';
$formhtml .= '</label>';
$formhtml .= '</div>';
$formhtml .= '<div style="display: flex; gap: 20px;">';
$observation_date = (isset($qor_data) && $qor_data->observation_date != '' &&  $qor_data->observation_date != '0000-00-00'  ?  date('d M, Y', strtotime($qor_data->observation_date)) : '');
$formhtml .= '<span><span style="font-weight: bold;">Date :</span>  ' .$observation_date  . '</span>';
$formhtml .= '<span style="flex-grow: 1; border-bottom: 1px solid #000;"></span>';
$formhtml .= '<span>  <span style="font-weight: bold;">Comments :</span>' . (isset($qor_data)  ? $qor_data->comments1 : '') . '</span>';
$formhtml .= '<span style="flex-grow: 1; border-bottom: 1px solid #000;"></span>';
$formhtml .= '</div>';
$formhtml .= '</td>';
$formhtml .= '</tr>';

$formhtml .= '<tr>';
$formhtml .= '<td>Basilius</td>';
$formhtml .= '<td>Design Consultant</td>';
$formhtml .= '<td>Client </td>';
$formhtml .= '</tr>';

$formhtml .= '</tbody>';
$formhtml .= '</table>';

// Comments Section
if (!empty($qor_comments)) {
    foreach ($qor_comments as $index => $comment) {
        // Find attachments for this comment (matching form_detail_id)
        $comment_attachments = array_filter($attachments, function ($attachment) use ($comment) {
            return $attachment['form_detail_id'] == $comment['id'];
        });

        // Display attachments in 2x2 grid if they exist
        if (!empty($comment_attachments)) {
            // Add page break before the image grid starts
            $formhtml .= '<div style="page-break-before: always;"></div>';

            $formhtml .= '<table width="100%" cellspacing="10" cellpadding="0" border="1" style="margin-top: 10px;">';

            $chunks = array_chunk($comment_attachments, 4); // Split into groups of 4 (2x2 grid per page)

            foreach ($chunks as $chunk_index => $chunk) {
                if ($chunk_index > 0) {
                    $formhtml .= '<div style="page-break-before: always;"></div>';
                }

                for ($i = 0; $i < 4; $i++) {
                    if ($i % 2 == 0) {
                        $formhtml .= '<tr>';
                    }

                    $formhtml .= '<td width="50%" style="text-align: center; vertical-align: top; height: 300px;">';
                    if (isset($chunk[$i])) {
                        $file_path = 'uploads/form_attachments/qorattachments/' . $chunk[$i]['form_id'] . '/' . $chunk[$i]['form_detail_id'] . '/' . $chunk[$i]['file_name'];

                        if (file_exists(FCPATH . $file_path)) {
                            $file_ext = pathinfo($chunk[$i]['file_name'], PATHINFO_EXTENSION);
                            $full_path = FCPATH . $file_path;
                            $base64 = base64_encode(file_get_contents($full_path));
                            $mime_type = mime_content_type($full_path);

                            // Check if it's an image (you can expand this list if needed)
                            if (in_array(strtolower($file_ext), ['jpg', 'jpeg', 'png', 'gif'])) {
                                $formhtml .= '<img src="data:' . $mime_type . ';base64,' . $base64 . '" style="max-width: 100%; max-height: 500px;">';
                            } else {
                                $formhtml .= '<div style="padding: 10px; border: 1px solid #ccc;">File: ' . $chunk[$i]['file_name'] . '</div>';
                            }
                        } else {
                            $formhtml .= '<div style="color: red;">File not found: ' . $chunk[$i]['file_name'] . '</div>';
                        }
                    } else {
                        $formhtml .= '&nbsp;';
                    }
                    $formhtml .= '</td>';

                    if ($i % 2 == 1) {
                        $formhtml .= '</tr>';
                    }
                }
            }

            $formhtml .= '</table>';

            // Display the comment
            $formhtml .= '<table width="100%" cellspacing="0" cellpadding="5" border="1" style="margin-top: 20px;">';
            $formhtml .= '<tr>';
            $formhtml .= '<td>' . htmlspecialchars($comment['comments']) . '</td>';
            $formhtml .= '</tr>';
            $formhtml .= '</table>';
        }

        // Add space between comment sections
        if ($index < count($qor_comments) - 1) {
            $formhtml .= '<div style="margin-bottom: 30px;"></div>';
        }
    }
}

$pdf->writeHTML($formhtml, true, false, true, false, '');
