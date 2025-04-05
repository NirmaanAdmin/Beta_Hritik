<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="panel_s">
               <div class="panel-body">
                  <h4><?php echo _l('meeting_agenda'); ?></h4>
                  <!-- Correct Create URL with module name -->
                  <a href="<?php echo admin_url('meeting_management/agendaController/create'); ?>" class="btn btn-success"><?php echo _l('create_new_agenda'); ?></a>

                  <table class="table table-bordered">
                     <thead>
                        <tr>
                           <th><?php echo _l('meeting_title'); ?></th>
                           <th><?php echo _l('meeting_date'); ?></th>
                           <!-- <th><?php echo _l('project'); ?></th> -->
                           <th><?php echo _l('options'); ?></th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php if (!empty($agendas)) : ?>
                           <?php foreach ($agendas as $agenda) : ?>
                              <tr>
                                 <td><?php echo $agenda['meeting_title']; ?></td>
                                 <td><?php echo $agenda['meeting_date']; ?></td>
                                 <!-- <td><?php echo isset($agenda['project_name']) ? $agenda['project_name'] : 'N/A'; ?></td> -->
                                 <td>
                                    <!-- Correct Edit and Delete URLs with module name -->
                                    <?php if ($agenda['flag'] == 0) { ?>
                                       <a href="<?php echo admin_url('meeting_management/agendaController/create/' . $agenda['id']); ?>" class="btn btn-info"><?php echo _l('Edit Agenda'); ?></a>
                                    <?php  } ?>

                                    <a href="<?php echo admin_url('meeting_management/agendaController/delete/' . $agenda['id']); ?>" class="btn btn-danger"><?php echo _l('delete'); ?></a>

                                    <?php
                                    if ($agenda['flag'] == 1) { ?>
                                       <a href="<?php echo admin_url('meeting_management/minutesController/index/' . $agenda['id']); ?>" class="btn btn-primary"><?php echo _l('edit_converted_metting'); ?></a>
                                    <?php } else { ?>
                                       <a href="<?php echo admin_url('meeting_management/minutesController/convert_to_minutes/' . $agenda['id']); ?>" class="btn btn-primary"><?php echo _l('meeting_convert_to_minutes'); ?></a>
                                    <?php }
                                    ?>


                                    <!-- View Meeting Button -->
                                    <a href="<?php echo admin_url('meeting_management/agendaController/view_meeting/' . $agenda['id']); ?>" class="btn btn-secondary"><?php echo _l('view_meeting'); ?></a>
                                 </td>
                              </tr>
                           <?php endforeach; ?>
                        <?php else : ?>
                           <tr>
                              <td colspan="4" class="text-center"><?php echo _l('no_agendas_found'); ?></td>
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