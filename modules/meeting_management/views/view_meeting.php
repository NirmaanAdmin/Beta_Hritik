<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<title><?php echo _l('view_meeting'); ?></title>
<?php init_head(); ?>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <!-- Meeting Details Section -->
                  <h4><?php echo _l('meeting_details'); ?></h4>
                  <table class="table table-bordered">
                     <tr>
                        <td><strong><?php echo _l('meeting_title'); ?>:</strong></td>
                        <td><?php echo isset($meeting['meeting_title']) ? $meeting['meeting_title'] : 'N/A'; ?></td>
                     </tr>
                     <tr>
                        <td><strong><?php echo _l('meeting_date'); ?>:</strong></td>
                        <td><?php echo isset($meeting['meeting_date']) ? $meeting['meeting_date'] : 'N/A'; ?></td>
                     </tr>
                     <tr>
                        <td><strong><?php echo _l('agenda'); ?>:</strong></td>
                        <td><?php echo isset($meeting['agenda']) ? $meeting['agenda'] : 'N/A'; ?></td>
                     </tr>
                     <!-- New Row for Meeting Notes -->
                     <tr>
                        <td><strong><?php echo _l('meeting_notes'); ?></strong></td>
                        <td><?php echo !empty($meeting_notes) ? $meeting_notes : 'N/A'; ?></td>
                     </tr>
                  </table>

                  <!-- Participants Table -->
                  <h4><?php echo _l('participants'); ?></h4>
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th><?php echo _l('Participant Name'); ?></th>
                           <th><?php echo _l('Email'); ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if (!empty($participants)) : ?>
                           <?php foreach ($participants as $participant) : ?>
                              <tr>
                                 <td><?php echo isset($participant['firstname']) ? $participant['firstname'] . ' ' . $participant['lastname'] : 'N/A'; ?></td>
                                 <td><?php echo isset($participant['email']) ? $participant['email'] : 'N/A'; ?></td>
                              </tr>
                           <?php endforeach; ?>
                        <?php else : ?>
                           <tr>
                              <td colspan="2" class="text-center"><?php echo _l('No Participants Found'); ?></td>
                           </tr>
                        <?php endif; ?>
                     </tbody>
                  </table>
                  <h4><?php echo _l('Other Participants'); ?></h4>

                  <?php
                  $other_participants = $other_participants[0]['other_participants'] ?? ''; 
                  ?>

                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th><?php echo _l('Name'); ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td><?php echo $other_participants; ?></td>
                        </tr>
                     </tbody>
                  </table>


                  <!-- Tasks Section -->
                  <h4><?php echo _l('tasks'); ?></h4>
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th><?php echo _l('task_title'); ?></th>
                           <th><?php echo _l('assigned_to'); ?></th>
                           <th><?php echo _l('due_date'); ?></th>
                           <th><?php echo _l('status'); ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if (!empty($tasks)) : ?>
                           <?php foreach ($tasks as $task) : ?>
                              <tr>
                                 <td><?php echo isset($task['task_title']) ? $task['task_title'] : 'N/A'; ?></td>
                                 <td><?php echo isset($task['firstname']) ? $task['firstname'] . ' ' . $task['lastname'] : 'N/A'; ?></td>
                                 <td><?php echo isset($task['due_date']) ? $task['due_date'] : 'N/A'; ?></td>
                                 <td><?php echo isset($task['status']) && $task['status'] == 1 ? _l('completed') : _l('not_completed'); ?></td>
                              </tr>
                           <?php endforeach; ?>
                        <?php else : ?>
                           <tr>
                              <td colspan="4" class="text-center"><?php echo _l('no_tasks_found'); ?></td>
                           </tr>
                        <?php endif; ?>
                     </tbody>
                  </table>
                  <?php
                  if (isset($attachments) && count($attachments) > 0) {
                     foreach ($attachments as $value) {
                        echo '<div class="col-md-3">';
                        $path = get_upload_path_by_type('meeting_management') . 'agenda_meeting/' . $value['rel_id'] . '/' . $value['file_name'];
                        $is_image = is_image($path);
                        if ($is_image) {
                           echo '<div class="preview_image">';
                        }
                  ?>
                        <a href="<?php echo site_url('download/file/meeting_management/' . $value['id']); ?>" class="display-block mbot5" <?php if ($is_image) { ?> data-lightbox="attachment-purchase-<?php echo $value['rel_id']; ?>" <?php } ?>>
                           <i class="<?php echo get_mime_class($value['filetype']); ?>"></i> <?php echo $value['file_name']; ?>
                           <?php if ($is_image) { ?>
                              <img class="mtop5" src="<?php echo site_url('download/preview_image?path=' . protected_file_url_by_path($path) . '&type=' . $value['filetype']); ?>" style="height: 165px;">
                           <?php } ?>
                        </a>
                        <?php
                        // echo '</div>';
                        // echo '<a href="' . admin_url('meeting_management/minutesController/delete_attachment/' . $value['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
                        ?>
                        <?php if ($is_image) {
                           echo '</div>';
                        } ?>
                  <?php echo '</div>';
                     }
                  } ?>

                  <!-- Export as PDF Button -->
                  <div class="btn-bottom-toolbar text-right">
                     <a href="<?php echo admin_url('meeting_management/agendaController/export_to_pdf/' . $meeting['meeting_id']); ?>" class="btn btn-info">
                        <?php echo _l('export_as_pdf'); ?>
                     </a>
                  </div>

               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php init_tail(); ?>
</body>

</html>