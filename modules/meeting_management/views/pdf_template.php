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
        h1 { 
            color: #4CAF50; 
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .details-table, .details-table th, .details-table td {
            border: 1px solid #ddd;
        }
        .details-table th, .details-table td {
            padding: 10px;
            text-align: left;
        }
        .details-table th {
            background-color: #f2f2f2;
            color: #333;
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

    <div class="logo">
        <!-- Fetch and display the company logo dynamically -->
        <img src="<?php echo get_company_logo(); ?>" alt="Company Logo">
    </div>

    <h1>Meeting Details</h1>

    <!-- Meeting Information -->
    <table class="details-table">
        <tr>
            <th>Meeting Title</th>
            <td><?php echo $meeting['meeting_title']; ?></td>
        </tr>
        <tr>
            <th>Meeting Date</th>
            <td><?php echo date('F d, Y h:i A', strtotime($meeting['meeting_date'])); ?></td>
        </tr>
        <tr>
            <th>Agenda</th>
            <td><?php echo nl2br($meeting['agenda']); ?></td>
        </tr>
    </table>

    <!-- Meeting Notes Section -->
    <h2 class="section-title">Meeting Notes</h2>
    <table class="details-table">
        <tr>
            <td>
                <!-- Check if meeting notes are available -->
                <?php echo !empty($meeting_notes) ? nl2br($meeting_notes) : 'No meeting notes available.'; ?>
            </td>
        </tr>
    </table>

    <!-- Participants Section -->
    <?php if (!empty($participants)) : ?>
        <h2 class="section-title">Participants</h2>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Participant Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($participants as $participant) : ?>
                    <tr>
                        <td><?php echo $participant['firstname'] . ' ' . $participant['lastname']; ?></td>
                        <td><?php echo $participant['email']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Tasks Section -->
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

    <!-- Footer Section -->
    <div class="footer">
        <!-- Display the company name dynamically -->
        <p>&copy; <?php echo date('Y'); ?> <?php echo get_option('companyname'); ?>. All Rights Reserved.</p>
    </div>

</body>
</html>
