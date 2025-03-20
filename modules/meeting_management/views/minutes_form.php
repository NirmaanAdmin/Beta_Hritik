<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Set the page title -->
<title><?php echo _l('meeting_minutes'); ?></title>
<?php init_head(); ?>
<style type="text/css">
   .cke_notification {
      display: none !important;
   }
</style>

<!-- Add CKEditor and SweetAlert -->
<script src="<?php echo base_url('modules/meeting_management/assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('modules/meeting_management/assets/js/sweetalert2@11.js'); ?>"></script>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h4><?php echo _l('meeting_minutes'); ?></h4>

                  <!-- Minutes of Meeting Form -->
                  <?php echo form_open(admin_url('meeting_management/minutesController/save_minutes_and_tasks/' . $agenda_id), array('id' => 'minutes-tasks-form')); ?>
                  <input type="hidden" name="agenda_id" value="<?php echo $agenda_id; ?>">
                  <div class="form-group">
                     <label for="minutes"><?php echo _l('meeting_minutes'); ?></label>
                     <?php
                     $minutes_val = '';
                     $minutes_val = isset($minutes) ? $minutes->minutes : '';
                     if(empty($minutes_val)) {
                        $minutes_val = isset($minutes) ? nl2br($minutes->agenda) : '';
                     }
                     ?>
                     <textarea id="minutes" name="minutes" class="form-control" required><?php echo $minutes_val; ?></textarea>
                  </div>

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
                     <button type="submit" id="save-all" class="btn btn-info"><?php echo _l('save_all'); ?></button>
                  </div>

                  <?php echo form_close(); ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php init_tail(); ?>

<!-- Initialize CKEditor on the 'minutes' textarea -->
<script>
   $(function(){
      // Check if CKEditor is available and initialize it
      if (typeof CKEDITOR !== 'undefined') {
         CKEDITOR.replace('minutes', {
            toolbar: 'Basic',  // You can configure the toolbar as per your needs
            height: 200
         });
      }
   });
</script>

<script>
    $(document).ready(function() {
        var taskCount = <?php echo !empty($tasks) ? count($tasks) : 0; ?>;

        // Use Perfex's default validation method
        $('#minutes-tasks-form').validate({
            submitHandler: function(form) {
                form.submit();  // Submit the form only if validation passes
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
                        data: { task_id: taskId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                row.remove();  // Remove task row from front-end if deleted successfully
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
</script>

</body>
</html>
