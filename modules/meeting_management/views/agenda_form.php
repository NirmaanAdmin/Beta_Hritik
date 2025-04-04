<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <?php 
         if(!empty($agenda->id)){
           echo  form_open_multipart(admin_url('meeting_management/agendaController/create/'.$agenda->id.''), array('id' => 'agenda-submit-form')); 
         }else{
           echo form_open_multipart(admin_url('meeting_management/agendaController/create'), array('id' => 'agenda-submit-form')); 
         }
         ?>
         
         <div class="col-md-12 left-column">
            <div class="panel_s">
               <div class="panel-body">


                  <!-- Client Dropdown -->
                  <!-- <div class="form-group">
                     <label for="client_id"><?php echo _l('select_client'); ?></label>
                     <select id="client_id" name="client_id" class="form-control" required>
                        <option value=""><?php echo _l('select_client'); ?></option>
                        <?php foreach ($clients as $client) : ?>
                           <option value="<?php echo $client['userid']; ?>">
                              <?php echo $client['company']; ?>
                           </option>
                        <?php endforeach; ?>
                     </select>
                  </div> -->

                  <!-- Project Dropdown (Initially empty, populated via Ajax) -->
                  <!-- <div class="form-group">
                     <label for="project_id"><?php echo _l('select_project'); ?></label>
                     <select id="project_id" name="project_id" class="form-control" required>
                        <option value=""><?php echo _l('select_project'); ?></option>
                     </select>
                  </div> -->

                  <!-- Meeting Title -->
                  <div class="form-group">
                     <label for="meeting_title"><?php echo _l('meeting_title'); ?></label>
                     <input type="text" id="meeting_title" name="meeting_title" class="form-control" value="<?php echo isset($agenda) && isset($agenda->meeting_title) ? htmlspecialchars($agenda->meeting_title) : ''; ?>" required>
                  </div>

                  <!-- Meeting Date -->
                  <div class="form-group">
                     <label for="meeting_date"><?php echo _l('meeting_date'); ?></label>
                     <input type="datetime-local" id="meeting_date" name="meeting_date"  value="<?php echo isset($agenda) && isset($agenda->meeting_date) ? htmlspecialchars($agenda->meeting_date) : ''; ?>" class="form-control" required>
                  </div>

                  <!-- Agenda -->
                  <div class="form-group">
                     <label for="agenda"><?php echo _l('meeting_agenda'); ?></label>
                     <!-- <textarea id="agenda" name="agenda" class="form-control" required></textarea>  -->

                     <?php
                     if($agenda->agenda != '' && $agenda->agenda != null){
                       $deafult_val = $agenda->agenda;
                     }else{

                        $deafult_val = '
   
   
                              <table border="1" cellspacing="0" cellpadding="8" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 14px;">
                                 <thead style="background-color: #f2f2f2;">
                                    <tr>
                                          <th style="border: 1px solid #ccc; text-align: center;">Sr. No.</th>
                                          <th style="border: 1px solid #ccc; text-align: center;">Area</th>
                                          <th style="border: 1px solid #ccc; text-align: center;">Description</th>
                                          <th style="border: 1px solid #ccc; text-align: center;">Decision</th>
                                          <th style="border: 1px solid #ccc; text-align: center;">Action</th>
                                          <th style="border: 1px solid #ccc; text-align: center;">Action By</th>
                                          <th style="border: 1px solid #ccc; text-align: center;">Target Date</th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                    <tr>
                                          <td style="border: 1px solid #ccc;text-align: center;">1</td>
                                          <td style="border: 1px solid #ccc;text-align: center;"></td>
                                          <td style="border: 1px solid #ccc;text-align: center;"></td>
                                          <td style="border: 1px solid #ccc;text-align: center;"></td>
                                          <td style="border: 1px solid #ccc;text-align: center;"></td>
                                          <td style="border: 1px solid #ccc;text-align: center;"></td>
                                          <td style="border: 1px solid #ccc;text-align: center;"></td>
                                    </tr>
                                 </tbody>
                              </table><br>
                              ';
                     }

                     ?>

                     <?php echo render_textarea('agenda', '', $deafult_val, array(), array(), 'mtop15', 'tinymce'); ?>

                  </div>

                  <!-- Submit Button -->
                  <div class="btn-bottom-toolbar text-right">
                     <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                  </div>


               </div>
            </div>
            <div class="panel-body">
               <label for="attachment"><?php echo _l('attachment'); ?></label>
               <div class="attachments">
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
            </div>
            
         </div>
         <?php echo form_close(); ?>
      </div>
   </div>

   <?php init_tail(); ?>

   <!-- jQuery to handle client selection and load projects -->
   <script>
      $(document).ready(function() {
         $('#client_id').on('change', function() {
            var client_id = $(this).val();
            if (client_id) {
               $.ajax({
                  url: '<?php echo admin_url("meeting_management/agendaController/get_projects_by_client/"); ?>' + client_id,
                  type: 'GET',
                  dataType: 'json',
                  success: function(data) {
                     $('#project_id').empty();
                     $('#project_id').append('<option value=""><?php echo _l("select_project"); ?></option>');
                     $.each(data, function(key, value) {
                        $('#project_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                     });
                  },
                  error: function() {
                     alert('Error retrieving projects');
                  }
               });
            } else {
               $('#project_id').empty();
               $('#project_id').append('<option value=""><?php echo _l("select_project"); ?></option>');
            }
         });

         setInterval(function() {
           update_mom_list();
         }, 5000);

         function update_mom_list() {
            var data = {};
            data.id = <?php echo $agenda->id; ?>;
            data.agenda = tinymce.get('agenda').getContent();
            $.post(admin_url + 'meeting_management/agendaController/update_mom_list', data).done(function(response){
            });
         }
      });
   </script>

   </body>

   </html>