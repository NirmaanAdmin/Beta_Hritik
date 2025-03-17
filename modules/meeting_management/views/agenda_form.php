<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-5 left-column">
            <div class="panel_s">
               <div class="panel-body">
               <?php echo form_open(admin_url('meeting_management/agendaController/create'), array('id' => 'agenda-submit-form')); ?>

               <!-- Client Dropdown -->
               <div class="form-group">
                  <label for="client_id"><?php echo _l('select_client'); ?></label>
                  <select id="client_id" name="client_id" class="form-control" required>
                     <option value=""><?php echo _l('select_client'); ?></option>
                     <?php foreach ($clients as $client) : ?>
                        <option value="<?php echo $client['userid']; ?>">
                           <?php echo $client['company']; ?>
                        </option>
                     <?php endforeach; ?>
                  </select>
               </div>

               <!-- Project Dropdown (Initially empty, populated via Ajax) -->
               <div class="form-group">
                  <label for="project_id"><?php echo _l('select_project'); ?></label>
                  <select id="project_id" name="project_id" class="form-control" required>
                     <option value=""><?php echo _l('select_project'); ?></option>
                  </select>
               </div>

               <!-- Meeting Title -->
               <div class="form-group">
                  <label for="meeting_title"><?php echo _l('meeting_title'); ?></label>
                  <input type="text" id="meeting_title" name="meeting_title" class="form-control" required>
               </div>

               <!-- Meeting Date -->
               <div class="form-group">
                  <label for="meeting_date"><?php echo _l('meeting_date'); ?></label>
                  <input type="datetime-local" id="meeting_date" name="meeting_date" class="form-control" required>
               </div>

               <!-- Agenda -->
               <div class="form-group">
                  <label for="agenda"><?php echo _l('meeting_agenda'); ?></label>
                  <textarea id="agenda" name="agenda" class="form-control" required></textarea>
               </div>

               <!-- Submit Button -->
               <div class="btn-bottom-toolbar text-right">
                  <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
               </div>

               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </div>
</div>

<?php init_tail(); ?>

<!-- jQuery to handle client selection and load projects -->
<script>
$(document).ready(function() {
    $('#client_id').on('change', function() {
        var client_id = $(this).val();
        if(client_id) {
            $.ajax({
                url: '<?php echo admin_url("meeting_management/agendaController/get_projects_by_client/"); ?>' + client_id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#project_id').empty();
                    $('#project_id').append('<option value=""><?php echo _l("select_project"); ?></option>');
                    $.each(data, function(key, value) {
                        $('#project_id').append('<option value="'+ value.id +'">'+ value.name +'</option>');
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
});
</script>

</body>
</html>
