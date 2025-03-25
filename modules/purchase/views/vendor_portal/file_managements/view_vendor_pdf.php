<div class="modal fade _project_file modal_index" tabindex="-1" role="dialog" data-toggle="modal">
   <div class="modal-dialog full-screen-modal dialog_withd" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" onclick="close_modal_preview(); return false;"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo pur_html_entity_decode($file->name); ?></h4>
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12 border-right project_file_area">
                  <?php
                  $folder = 'files';
                  $path = DRAWING_MANAGEMENT_MODULE_UPLOAD_FOLDER.'/'.$folder.'/'.$file->parent_id.'/'.$file->name;
                  ?>
                  <iframe src="<?php echo base_url(DRAWING_MANAGEMENT_PATH.$folder.'/'.$file->parent_id.'/'.$file->name); ?>" height="100%" width="100%" frameborder="0"></iframe>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" onclick="close_modal_preview(); return false;"><?php echo _l('close'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php $discussion_lang = get_project_discussions_language_array(); ?>
<?php require 'modules/purchase/assets/js/_file_js.php';?>

<script>
   function close_modal_preview(){
     "use strict"; 
    $('._project_file').modal('hide');
   }
</script>
