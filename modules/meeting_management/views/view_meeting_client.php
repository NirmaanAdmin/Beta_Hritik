<h2><?php echo _l('meeting_minutes'); ?></h2>

<table class="table">
    <tr>
        <th><?php echo _l('meeting_title'); ?></th>
        <td><?php echo $meeting['meeting_title']; ?></td>
    </tr>
    <tr>
        <th><?php echo _l('meeting_date'); ?></th>
        <td><?php echo date('F d, Y h:i A', strtotime($meeting['meeting_date'])); ?></td>
    </tr>
    <tr>
        <th><?php echo _l('agenda'); ?></th>
        <td><?php echo nl2br($meeting['agenda']); ?></td>
    </tr>
    <tr>
            <th><?php echo _l('meeting_minutes'); ?></th>
            <td><?php echo nl2br($meeting['minutes']); // Display the meeting notes ?></td>
        </tr>
</table>
