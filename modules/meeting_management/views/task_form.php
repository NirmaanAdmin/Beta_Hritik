<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-5 left-column">
            <div class="panel_s">
               <div class="panel-body">
               <?php echo form_open(admin_url('meeting_management/taskController/create/' . $agenda_id), array('id' => 'task-submit-form')); ?>

               <div class="form-group">
                  <label for="task_title"><?php echo _l('meeting_task_title'); ?></label>
                  <input type="text" id="task_title" name="task_title" class="form-control" required>
               </div>

               <div class="form-group">
    <label for="assigned_to"><?php echo _l('meeting_task_assigned_to'); ?></label>
    <select id="assigned_to" name="assigned_to" class="form-control" required>
        <?php if (!empty($staff_members)) : ?>
            <?php foreach ($staff_members as $staff) : ?>
                <option value="<?php echo $staff['staffid']; ?>">
                    <?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?>
                </option>
            <?php endforeach; ?>
        <?php else : ?>
            <option value=""><?php echo _l('no_staff_available'); ?></option>
        <?php endif; ?>
    </select>
</div>


               <div class="form-group">
                  <label for="due_date"><?php echo _l('meeting_task_due_date'); ?></label>
                  <input type="date" id="due_date" name="due_date" class="form-control" required>
               </div>

               <!-- Submit Button -->
               <div class="btn-bottom-toolbar text-right">
                  <button type="submit" class="btn btn-info"><?php echo _l('save'); ?></button>
               </div>

               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </div>
</div>

<?php init_tail(); ?>
</body>
</html>
