<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head(); ?>
<style>
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
<div id="wrapper">
   <div class="content">
      <div class="row ">
         <?php
         if (!empty($agenda->id)) {
            echo  form_open_multipart(admin_url('meeting_management/agendaController/create/' . $agenda->id . ''), array('id' => 'agenda-submit-form'));
         } else {
            echo form_open_multipart(admin_url('meeting_management/agendaController/create'), array('id' => 'agenda-submit-form'));
         }
         if (isset($agenda)) {
            echo form_hidden('isedit');
          }
         ?>

         <div class="col-md-12 left-column">
            <div class="panel_s">
               <div class="panel-body mom-items">


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
                     <input type="datetime-local" id="meeting_date" name="meeting_date" value="<?php echo isset($agenda) && isset($agenda->meeting_date) ? htmlspecialchars($agenda->meeting_date) : ''; ?>" class="form-control" required>
                  </div>

                  <!-- Agenda -->
                  <div class="form-group">
                     <label for="agenda"><?php echo _l('agenda'); ?></label>
                     <!-- <textarea id="agenda" name="agenda" class="form-control" required></textarea>  -->

                     <?php
                     // if($agenda->agenda != '' && $agenda->agenda != null){
                     $additional_note = $agenda->additional_note;
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
                     <br>

                     <?php echo render_textarea('additional_note', 'Additional Note', $additional_note, array(), array(), 'mtop15', 'tinymce'); ?>

                  </div>


                  <!-- Submit Button -->
                  <div class="btn-bottom-toolbar text-right">
                     <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                  </div>

                  <div id="removed-items"></div>         
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