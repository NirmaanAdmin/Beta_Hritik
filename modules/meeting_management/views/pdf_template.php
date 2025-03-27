<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Meeting Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: left;
        }

        .details-table,
        .description-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .details-table,
        .details-table th,
        .details-table td,
        .description-table td {
            border: 1px solid #ddd;
        }

        .details-table th,
        .details-table td {
            padding: 6px;
            text-align: center;
            font-size: 13px;
        }

        .description-table th,
        .description-table td {
            padding: 6px;
            font-size: 13px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 30px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #666;
            padding: 10px 0;
        }
    </style>
</head>

<body>

    <?php
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
            echo $logo;
        }
    }
    ?>

    <h2>Minutes of Meeting</h2>

    <table class="details-table">
        <tr>
            <th style="width: 15%;">Project</th>
            <td style="width: 40%;">BGJ 2 Acre <?php echo get_project_name_by_id($meeting['project_id']); ?>-Annex Building</td>
            <td style="width: 15%;">Subject</td>
            <td style="width: 30%;"><?php echo $meeting['meeting_title']; ?></td>
        </tr>
        <tr>
            <th style="width: 15%;">Meeting Date & Time</th>
            <td style="width: 40%;"><?php echo date('d-M-y h:i A', strtotime($meeting['meeting_date'])); ?></td>
            <td style="width: 15%;">Minutes by</td>
            <td style="width: 30%;"><?php echo get_staff_full_name($meeting['created_by']); ?></td>
        </tr>
        <tr>
            <th style="width: 15%;">Venue</th>
            <td style="width: 40%;">BGJ site office</td>
            <td style="width: 15%;">MOM No</td>
            <td style="width: 30%;">BIL-MOM-SUR-<?php echo date('dmy', strtotime($meeting['meeting_date'])); ?></td>
        </tr>
    </table>

    <table class="details-table">
        <tr>
            <th style="width: 10%;"></th>
            <td style="width: 20%; font-weight: bold;">Company</td>
            <td style="width: 70%; font-weight: bold;">Participant’s Name</td>
        </tr>

        <!-- Row for BIL Company -->
        <tr>
            <td style="width: 10%;">1</td>
            <td style="width: 20%; text-align: left;">BIL</td>
            <td style="width: 70%; text-align: left;">
                <?php
                if (!empty($participants)) {
                    $all_participant = '';
                    foreach ($participants as $participant) {
                        if (!empty($participant['firstname']) || !empty($participant['lastname']) || !empty($participant['email'])) :
                            $all_participant .= $participant['firstname'] . ' ' . $participant['lastname'] . ', ';
                        endif;
                    }
                    $all_participant = rtrim($all_participant, ", ");
                    echo $all_participant;
                }
                ?>
            </td>
        </tr>

        <!-- Rows for Other Participants -->
        <?php
        // Ensure $other_participants is an array
        $other_participants = is_array($other_participants) ? $other_participants : [];

        if (!empty($other_participants)) {
            foreach ($other_participants as $index => $participant) {
                // Extract participant name and company name
                $participant_name = isset($participant['other_participants']) ? htmlspecialchars($participant['other_participants']) : '';
                $company_name = isset($participant['company_names']) ? htmlspecialchars($participant['company_names']) : '';
        ?>
                <tr>
                    <td style="width: 10%;"><?php echo $index + 2; ?></td> <!-- Increment index by 2 to account for the BIL row -->
                    <td style="width: 20%; text-align: left;"><?php echo $company_name; ?></td>
                    <td style="width: 70%; text-align: left;"><?php echo $participant_name; ?></td>
                </tr>
        <?php
            }
        }
        ?>
    </table>

    <table class="description-table">
        <tr>
            <td style="font-weight: bold;">Description</td>
        </tr>
        <tr>
            <td>
                <?php
                $meeting_notes_html = html_entity_decode($meeting_notes, ENT_QUOTES, 'UTF-8');

                echo !empty($meeting_notes) ? $meeting_notes_html : 'No meeting notes available.'; ?>
            </td>
        </tr>
    </table>

    <?php if (!empty($tasks)) : ?>
        <h2 class="section-title">Tasks Overview</h2>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task) : ?>
                    <tr>
                        <td><?php echo $task['task_title']; ?></td>
                        <td><?php echo $task['firstname'] . ' ' . $task['lastname']; ?></td>
                        <td><?php echo date('F d, Y', strtotime($task['due_date'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <table class="details-table">
        <tr>
            <th style="width: 20%; text-align: left;">Attachments</th>
            <td style="width: 80%; text-align: left;">None</td>
        </tr>
        <tr>
            <th style="width: 20%; text-align: left;">Distribution to</th>
            <td style="width: 80%; text-align: left;">All participants</td>
        </tr>
    </table>

    <p style="font-size: 13px;">If any comments on above minutes, please revert within 48 hours, after which time they will be held valid.</p>

    <!-- Footer Section -->
    <div class="footer">
        <!-- Display the company name dynamically -->
        <p>&copy; <?php echo date('Y'); ?> <?php echo get_option('companyname'); ?>. All Rights Reserved.</p>
    </div>

</body>

</html>