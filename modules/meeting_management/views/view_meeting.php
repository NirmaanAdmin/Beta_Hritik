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
                        <?php
                        if (!empty($participants)) : ?>
                           <?php foreach ($participants as $participant) : ?>
                              <?php
                              // Check if the participant has at least one valid field (non-empty firstname, lastname, or email)
                              if (!empty($participant['firstname']) || !empty($participant['lastname']) || !empty($participant['email'])) :
                              ?>
                                 <tr>
                                    <td><?php echo isset($participant['firstname']) || isset($participant['lastname'])
                                             ? trim($participant['firstname'] . ' ' . $participant['lastname'])
                                             : 'N/A'; ?></td>
                                    <td><?php echo isset($participant['email']) && !empty($participant['email'])
                                             ? $participant['email']
                                             : 'N/A'; ?></td>
                                 </tr>
                              <?php endif; ?>
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
                  // Extract all 'other_participants' and 'company_name' values into a single array
                  $all_other_participants = [];
                  foreach ($other_participants as $participant) {
                     if (!empty($participant['other_participants']) || !empty($participant['company_name'])) {
                        $all_other_participants[] = [
                           'name' => $participant['other_participants'] ?? '',
                           'company_name' => $participant['company_name'] ?? '',
                        ];
                     }
                  }
                  ?>

                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th><?php echo _l('Name'); ?></th>
                           <th><?php echo _l('Company'); ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if (!empty($all_other_participants)) : ?>
                           <?php foreach ($all_other_participants as $participant) : ?>
                              <tr>
                                 <td><?php echo htmlspecialchars($participant['name']); ?></td>
                                 <td><?php echo htmlspecialchars($participant['company_name']); ?></td>
                              </tr>
                           <?php endforeach; ?>
                        <?php else : ?>
                           <tr>
                              <td colspan="2">No participants found</td>
                           </tr>
                        <?php endif; ?>
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
                  <div class="col-md-12" id="meeting_attachments">
                     <?php
                     $file_html = '';
                     if (isset($attachments) && count($attachments) > 0) {
                        $file_html .= '<hr /><p class="bold text-muted">' . _l('Meeting Attachments') . '</p>';

                        foreach ($attachments as $value) {
                           $path = get_upload_path_by_type('meeting_management') . 'agenda_meeting/' . $value['rel_id'] . '/' . $value['file_name'];
                           $is_image = is_image($path);

                           $download_url = site_url('download/file/meeting_management/' . $value['id']);

                           $file_html .= '<div class="mbot15 row inline-block full-width" data-attachment-id="' . $value['id'] . '">
                <div class="col-md-8">';

                           // Preview button for images
                           if ($value['filetype'] != 'application/vnd.openxmlformats-officedoc  ') {
                              $file_html .= '<a name="preview-meeting-btn" 
                    onclick="preview_meeting_attachment(this); return false;" 
                    rel_id="' . $value['rel_id'] . '" 
                    id="' . $value['id'] . '" 
                    href="javascript:void(0);" 
                    class="mbot10 mright5 btn btn-success pull-left" 
                    data-toggle="tooltip" 
                    title="' . _l('preview_file') . '">
                    <i class="fa fa-eye"></i>
                </a>';
                           }

                           $file_html .= '<div class="pull-left"><i class="' . get_mime_class($value['filetype']) . '"></i></div>
                <a href="' . $download_url . '" target="_blank" download>
                    ' . $value['file_name'] . '
                </a>
                <br />
                <small class="text-muted">' . $value['filetype'] . '</small>
                </div>
                <div class="col-md-4 text-right">';

                           // Delete button with permission check
                           if ($value['staffid'] == get_staff_user_id() || is_admin()) {
                              $file_html .= '<a href="' . admin_url('meeting_management/minutesController/delete_attachment/' . $value['id']) . '" class="text-danger _delete"><i class="fa fa-times"></i></a>';
                           }

                           $file_html .= '</div></div>';
                        }

                        $file_html .= '<hr />';
                        echo pur_html_entity_decode($file_html);
                     }
                     ?>
                  </div>

                  <div id="meeting_file_data"></div>

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
<script>
   function preview_meeting_attachment(invoker) {
      "use strict";
      var id = $(invoker).attr('id');
      var rel_id = $(invoker).attr('rel_id');
      view_preview_meeting_attachment(id, rel_id);
   }

   function view_preview_meeting_attachment(id, rel_id) {
      "use strict";
      $('#meeting_file_data').empty();
      $("#meeting_file_data").load(admin_url + 'meeting_management/minutesController/file_meeting_preview/' + id + '/' + rel_id, function(response, status, xhr) {
         if (status == "error") {
            alert_float('danger', xhr.statusText);
         }
      });
   }

   function close_modal_preview() {
      "use strict";
      $('._project_file').modal('hide');
   }
</script>
</body>

</html>