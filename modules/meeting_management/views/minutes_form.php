<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style type="text/css">
   .cke_notification {
      display: none !important;
   }

   .margin_add_class {
      margin-top: 14px;
   }

   table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      font-size: 14px;
   }

   th,
   td {
      border: 1px solid #ccc;
      text-align: center;
      padding: 8px;
   }

   thead {
      background-color: #f2f2f2;
   }

   button {
      padding: 5px 10px;
   }

   img.images_w_table {
      width: 116px;
      height: 73px;
   }
</style>

<!-- Add CKEditor and SweetAlert -->
<script src="<?php echo base_url('modules/meeting_management/assets/js/sweetalert2@11.js'); ?>"></script>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <input type="hidden" id="flag" value="<?php echo $agenda->flag; ?>">
         <?php echo form_open_multipart(admin_url('meeting_management/minutesController/save_minutes_and_tasks/' . $agenda_id), array('id' => 'minutes-tasks-form'));
         if (isset($agenda)) {
            echo form_hidden('isedit');
         }
         ?>

         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body mom-items">
                  <h4><?php echo _l('meeting_minutes'); ?></h4>

                  <!-- Minutes of Meeting Form -->

                  <input type="hidden" name="agenda_id" value="<?php echo $agenda_id; ?>">
                  <div class="form-group">
                     <label for="minutes"><?php echo _l('meeting_minutes'); ?></label>
                     <?php
                     $minutes_val = '';
                     $minutes_val = isset($minutes) ? $minutes->minutes : '';
                     if (empty($minutes_val)) {
                        $minutes_val .= isset($minutes) ? nl2br($minutes->minutes) : '';
                        // $minutes_val .= '<p><strong>Decision -<br>Action -</strong></p>';
                     }
                     if ($agenda->flag == 1) {
                        $minutes_data = $minutes->minutes;
                     } else {
                        $minutes_data = $minutes->agenda;
                     }
                     $additional_note = isset($minutes) ? $minutes->additional_note : '';
                     ?>
                     <table class="mom-items-table items table-main-dpr-edit has-calculations no-mtop">
                        <thead>
                           <tr>
                              <th>Area/Head</th>
                              <th>Description</th>
                              <th>Decision</th>
                              <th>Action</th>
                              <th>Action By</th>
                              <th>Target Date</th>
                              <th>Attachments</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody class="mom_body">
                           <?php echo pur_html_entity_decode($mom_row_template); ?>
                        </tbody>
                     </table>
                     <?php echo render_textarea('additional_note', 'Additional Note', $additional_note, array(), array(), 'mtop15', 'tinymce'); ?>
                  </div>
                  <div id="removed-items"></div>    
                  <!-- Participants Selection -->
                  <div class="form-group">
                     <label for="participants"><?php echo _l('select_participants'); ?></label>
                     <select name="participants[]" id="participants" class="form-control selectpicker" multiple="multiple" data-live-search="true" required>
                        <?php foreach ($staff_members as $staff) : ?>
                           <option value="<?php echo $staff['staffid']; ?>" <?php echo !empty($selected_participants) && in_array($staff['staffid'], $selected_participants) ? 'selected' : ''; ?>>
                              <?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?> (Staff)
                           </option>
                        <?php endforeach; ?>
                        <?php if (!empty($clients) && is_array($clients)) : ?>
                           <?php foreach ($clients as $client) : ?>
                              <option value="<?php echo $client['userid']; ?>" <?php echo !empty($selected_participants) && in_array($client['userid'], $selected_participants) ? 'selected' : ''; ?>>
                                 <?php echo $client['company']; ?> (Client)
                              </option>
                           <?php endforeach; ?>
                        <?php endif; ?>
                     </select>
                  </div>
                  <div class="form-group">
                     <label for="other_participants"><?php echo _l('Participants'); ?></label>
                     <div id="other-participants-container">
                        <?php
                        // Initialize an empty array to store all other_participants values
                        $all_other_participants = [];

                        // Extract all 'other_participants' values into a single array
                        foreach ($other_participants as $participant) {
                           if (!empty($participant['other_participants'])) {
                              $all_other_participants[] = [
                                 'name' => $participant['other_participants'],
                                 'company_name' => $participant['company_names'] ?? '', // Add company name if available
                              ];
                           }
                        }

                        // Display input fields for each participant
                        if (!empty($all_other_participants)) {
                           foreach ($all_other_participants as $index => $participant) {
                              echo '<div class="input-group mb-2" style="width: 100%; display: flex; gap: 10px;margin-bottom: 14px;">';

                              // Participant Name Input (50% width)
                              echo '<input type="text" name="other_participants[]" class="form-control" value="' . htmlspecialchars($participant['name']) . '" style="flex: 1;" placeholder="Participant Name">';

                              // Company Name Input (50% width)
                              echo '<input type="text" name="company_names[]" class="form-control" value="' . htmlspecialchars($participant['company_name']) . '" style="flex: 1;" placeholder="Company Name">';

                              // Add "Remove" button only if there is more than one participant
                              if (count($all_other_participants) > 1) {
                                 echo '<button type="button" class="btn btn-danger remove-participant" style="margin-top: 0px;">Remove</button>';
                              }
                              echo '</div>';
                           }
                        } else {
                           // Default input fields without a "Remove" button
                           echo '<div class="input-group mb-2" style="width: 100%; display: flex; gap: 10px; margin-bottom: 14px;">';

                           // Participant Name Input (50% width)
                           echo '<input type="text" name="other_participants[]" class="form-control" style="flex: 1;" placeholder="Participant Name">';

                           // Company Name Input (50% width)
                           echo '<input type="text" name="company_names[]" class="form-control" style="flex: 1;" placeholder="Company Name">';

                           echo '</div>';
                        }
                        ?>
                     </div>
                     <button type="button" id="add-participant" class="btn btn-primary mt-2 pull-right " style="margin-left: 10px;">Add Participant</button>
                  </div>
                  <!-- Share Meeting Button -->
                  <div class="text-right">
                     <a href="<?php echo admin_url('meeting_management/minutesController/share_meeting/' . $agenda_id); ?>" class="btn btn-success">
                        <?php echo _l('share_meeting'); ?>
                     </a>
                  </div>
                  <hr>

                  <!-- Dynamic Task List -->
                  <h4><?php echo _l('task_overview'); ?></h4>
                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th><?php echo _l('meeting_task_title'); ?></th>
                           <th><?php echo _l('meeting_task_assigned_to'); ?></th>
                           <th><?php echo _l('meeting_task_due_date'); ?></th>
                           <th><?php echo _l('actions'); ?></th>
                        </tr>
                     </thead>
                     <tbody id="task-overview">
                        <!-- Existing tasks will be loaded here -->
                        <?php if (!empty($tasks)) : ?>
                           <?php foreach ($tasks as $task) : ?>
                              <tr>
                                 <td>
                                    <input type="text" name="tasks[<?php echo $task['id']; ?>][title]" class="form-control" value="<?php echo $task['task_title']; ?>" required>
                                 </td>
                                 <td>
                                    <select name="tasks[<?php echo $task['id']; ?>][assigned_to]" class="form-control" required>
                                       <?php foreach ($staff_members as $staff) : ?>
                                          <option value="<?php echo $staff['staffid']; ?>" <?php echo ($staff['staffid'] == $task['assigned_to']) ? 'selected' : ''; ?>>
                                             <?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?>
                                          </option>
                                       <?php endforeach; ?>
                                    </select>
                                 </td>
                                 <td>
                                    <input type="date" name="tasks[<?php echo $task['id']; ?>][due_date]" class="form-control" value="<?php echo $task['due_date']; ?>" required>
                                 </td>
                                 <td>
                                    <button type="button" class="btn btn-danger btn-sm delete-existing-task" data-task-id="<?php echo $task['id']; ?>">
                                       <?php echo _l('delete_task'); ?>
                                    </button>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        <?php else : ?>
                           <tr>
                              <td colspan="4" class="text-center"><?php echo _l('no_tasks_found'); ?></td>
                           </tr>
                        <?php endif; ?>
                     </tbody>
                  </table>

                  <!-- Button to Add More Tasks -->
                  <button type="button" id="add-task" class="btn btn-primary"><?php echo _l('add_another_task'); ?></button>

                  <hr>

                  <div class="btn-bottom-toolbar text-right">
                     <button
                        type="button"
                        id="back"
                        class="btn btn-default"
                        onclick="window.location.href='<?php echo site_url('admin/meeting_management/agendaController/index'); ?>'">
                        <?php echo _l('Back'); ?>
                     </button>
                     <button type="submit" id="save-all" class="btn btn-info"><?php echo _l('save_all'); ?></button>
                  </div>


               </div>
            </div>
            <div class="panel-body" style="margin-bottom: 10px;">
               <label for="attachment"><?php echo _l('attachment'); ?></label>
               <div class="attachments" style="margin-bottom: 10px;">
                  <div class="attachment">
                     <div class="col-md-5 form-group" style="padding-left: 0px;">
                        <div class="input-group">
                           <input type="file" extension="<?php echo str_replace(['.', ' '], '', get_option('ticket_attachments_file_extensions')); ?>" filesize="<?php echo file_upload_max_size(); ?>" class="form-control" name="attachments[0]" accept="<?php echo get_ticket_form_accepted_mimes(); ?>">
                           <span class="input-group-btn">
                              <button class="btn btn-success add_more_attachments p8" type="button"><i class="fa fa-plus"></i></button>
                           </span>
                        </div>
                     </div>
                  </div>
               </div>
               <br /> <br />

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
            </div>
         </div>
         <?php echo form_close(); ?>
      </div>
   </div>
</div>

<?php init_tail(); ?>

<script>
   $(document).ready(function() {
      var taskCount = <?php echo !empty($tasks) ? count($tasks) : 0; ?>;

      // Use Perfex's default validation method
      $('#minutes-tasks-form').validate({
         submitHandler: function(form) {
            form.submit(); // Submit the form only if validation passes
         }
      });

      // Add new task row
      $('#add-task').on('click', function() {
         taskCount++;
         const newTaskId = `new_${taskCount}`;

         $('#task-overview').append(`
                <tr data-task-id="${newTaskId}">
                    <td>
                        <input type="text" name="new_tasks[${taskCount}][title]" class="form-control" required>
                    </td>
                    <td>
                        <select name="new_tasks[${taskCount}][assigned_to]" class="form-control" required>
                            <?php foreach ($staff_members as $staff) : ?>
                                <option value="<?php echo $staff['staffid']; ?>"><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="date" name="new_tasks[${taskCount}][due_date]" class="form-control" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm delete-existing-task" data-task-id="${newTaskId}"><?php echo _l('delete_task'); ?></button>
                    </td>
                </tr>
            `);
      });

      // Remove existing task row and delete from database using SweetAlert for confirmation
      $(document).on('click', '.delete-existing-task', function() {
         const taskId = $(this).data('task-id');
         const row = $(this).closest('tr');

         Swal.fire({
            title: '<?php echo _l('confirm_delete_task'); ?>',
            text: "<?php echo _l('confirm_delete_task_message'); ?>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?php echo _l('yes_delete_it'); ?>',
            cancelButtonText: '<?php echo _l('cancel'); ?>'
         }).then((result) => {
            if (result.isConfirmed) {
               $.ajax({
                  url: '<?php echo admin_url('meeting_management/minutesController/delete_task'); ?>',
                  type: 'POST',
                  data: {
                     task_id: taskId
                  },
                  dataType: 'json',
                  success: function(response) {
                     if (response.success) {
                        row.remove(); // Remove task row from front-end if deleted successfully
                        alert_float('success', '<?php echo _l('task_deleted_successfully'); ?>');
                     } else {
                        alert_float('danger', '<?php echo _l('task_deletion_failed'); ?>');
                     }
                  },
                  error: function(xhr, status, error) {
                     alert_float('danger', '<?php echo _l('task_deletion_failed'); ?>');
                  }
               });
            }
         });
      });
   });

   $('#participants').selectpicker();

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

   $(document).ready(function() {
      // Function to check if "Remove" buttons should be shown
      function updateRemoveButtons() {

         const inputs = $('#other-participants-container .input-group');
         if (inputs.length > 1) {
            // Show "Remove" buttons for all input fields
            inputs.find('.remove-participant').show();

         } else {
            // Hide "Remove" buttons if there's only one input field
            inputs.find('.remove-participant').hide();
            // $('#add-participant').removeClass('margin_add_class');
         }
      }

      // Add new participant input field
      $('#add-participant').on('click', function() {
         // $('#add-participant').addClass('margin_add_class'); 
         const newInput = `
            <div class="input-group mb-2" style="width: 100%; display: flex; gap: 10px;margin-bottom: 14px;">
                <!-- Participant Name Input -->
                <input type="text" name="other_participants[]" class="form-control" style="flex: 1;" placeholder="Participant Name">
                
                <!-- Company Name Input -->
                <input type="text" name="company_names[]" class="form-control" style="flex: 1;" placeholder="Company Name">
                
                <!-- Remove Button -->
                <button type="button" class="btn btn-danger remove-participant" style="margin-top: 0px; display: none;">Remove</button>
            </div>
        `;
         $('#other-participants-container').append(newInput);
         updateRemoveButtons(); // Update visibility of "Remove" buttons
      });

      // Remove participant input field
      $(document).on('click', '.remove-participant', function() {
         $(this).closest('.input-group').remove();
         updateRemoveButtons(); // Update visibility of "Remove" buttons
      });

   });
</script>

</body>

</html>

<script type="text/javascript">
   $(document).on('click', '.mom-add-item-to-table', function(event) {
      "use strict";

      var data = 'undefined';
      data = typeof(data) == 'undefined' || data == 'undefined' ? mom_get_item_preview_values() : data;
      var table_row = '';
      var item_key = lastAddedItemKey ? lastAddedItemKey += 1 : $("body").find('.mom-items-table tbody .item').length + 1;
      lastAddedItemKey = item_key;
      mom_get_item_row_template('newitems[' + item_key + ']', data.area, data.description, data.decision, data.action, data.staff, data.vendor, data.target_date, data.attachments, item_key).done(function(output) {
         table_row += output;

         $('.mom_body').append(table_row);
         var sourceInput = $("input[name='attachments']")[0];
         var targetInput = $("input[name='newitems[" + lastAddedItemKey + "][attachments]']")[0];
         if (sourceInput.files.length > 0) {
            var dataTransfer = new DataTransfer();
            for (var i = 0; i < sourceInput.files.length; i++) {
               dataTransfer.items.add(sourceInput.files[i]);
            }
            targetInput.files = dataTransfer.files;
         }
         init_selectpicker();
         pur_clear_item_preview_values();
         $('body').find('#items-warning').remove();
         $("body").find('.dt-loader').remove();
         return true;
      });
      return false;
   });

   function mom_get_item_row_template(name, area, description, decision, action, staff, vendor, target_date, attachments, item_key) {
      "use strict";

      jQuery.ajaxSetup({
         async: false
      });

      var d = $.post(admin_url + 'meeting_management/agendaController/get_mom_row_template', {
         name: name,
         area: area,
         description: description,
         decision: decision,
         action: action,
         staff: staff,
         vendor: vendor,
         target_date: target_date,
         item_key: item_key
      });
      jQuery.ajaxSetup({
         async: true
      });
      return d;
   }

   function mom_get_item_preview_values() {
      "use strict";

      var response = {};
      response.area = $('.mom-items-table .main textarea[name="area"]').val();
      response.description = $('.mom-items-table .main textarea[name="description"]').val();
      response.decision = $('.mom-items-table .main textarea[name="decision"]').val();
      response.action = $('.mom-items-table .main textarea[name="action"]').val();
      response.staff = $('.mom-items-table .main select[name="staff"]').val();
      response.vendor = $('.mom-items-table .main input[name="vendor"]').val();
      response.target_date = $('.mom-items-table .main input[name="target_date"]').val();
      return response;
   }

   function pur_clear_item_preview_values() {
      "use strict";

      var previewArea = $('.mom_body .main');
      previewArea.find('input').val('');
      previewArea.find('textarea').val('');
      previewArea.find('select')
         .prop('disabled', false) // Remove the disabled attribute
         .val('') // Clear the value
         .selectpicker('refresh'); // Refresh the selectpicker UI
   }

   function mom_delete_item(row, itemid, parent) {
      "use strict";

      $(row).parents('tr').addClass('animated fadeOut', function() {
         setTimeout(function() {
            $(row).parents('tr').remove();
            pur_calculate_total();
         }, 50);
      });
      if (itemid && $('input[name="isedit"]').length > 0) {
         $(parent + ' #removed-items').append(hidden_input('removed_items[]', itemid));
      }
   }
</script>