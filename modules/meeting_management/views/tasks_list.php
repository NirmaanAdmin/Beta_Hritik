<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h4><?php echo _l('meeting_tasks'); ?></h4>

                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th><?php echo _l('meeting_task_title'); ?></th>
                           <th><?php echo _l('meeting_task_assigned_to'); ?></th>
                           <th><?php echo _l('meeting_task_due_date'); ?></th>
                           <th><?php echo _l('meeting_task_status'); ?></th>
                        </tr>
                     </thead>
                  <tbody>
                     <?php if (!empty($tasks)) : ?>
                        <?php foreach ($tasks as $task) : ?>
                           <tr>
                              <td><?php echo $task['task_title']; ?></td>
                              <td><?php echo $task['firstname'] . ' ' . $task['lastname']; ?></td> <!-- Displaying the staff name -->
                              <td><?php echo $task['due_date']; ?></td>
                              <td><?php echo $task['status'] == 1 ? 'Completed' : 'Not Completed'; ?></td>
                           </tr>
                        <?php endforeach; ?>
                     <?php else : ?>
                        <tr>
                           <td colspan="4" class="text-center"><?php echo _l('no_tasks_found'); ?></td>
                        </tr>
                     <?php endif; ?>
                  </tbody>

                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php init_tail(); ?>
</body>
</html>
