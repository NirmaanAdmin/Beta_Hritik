<?php if (!empty($meetings)) : ?>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><?php echo _l('meeting_title'); ?></th>
                    <th><?php echo _l('meeting_date'); ?></th>
                    <th><?php echo _l('agenda'); ?></th>
                    <th><?php echo _l('actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($meetings as $meeting) : ?>
                    <tr>
                        <td><?php echo $meeting['meeting_title']; ?></td>
                        <td><?php echo date('F d, Y', strtotime($meeting['meeting_date'])); ?></td>
                        <td><?php echo $meeting['agenda']; ?></td>
                        <td>
                        <a href="<?php echo site_url('meeting_management/clients/view_meeting/' . $meeting['id']); ?>" class="btn btn-info">
    <?php echo _l('view_meeting'); ?>
</a>

                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else : ?>
    <p><?php echo _l('no_meetings_found'); ?></p>
<?php endif; ?>
