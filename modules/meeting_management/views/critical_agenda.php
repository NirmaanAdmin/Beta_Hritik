<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head();  ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s invoice-item-table">
                    <div class="panel-body">
                        <div class="row" style="display: flex;margin-bottom: 16px;">
                            <h4><?php echo _l('meeting_critical_agenda'); ?></h4>
                            <button class="btn btn-info pull-right mright10 display-block" style="margin-left: 10px;" data-toggle="modal" data-target="#addNewRowModal">
                                <i class="fa fa-plus"></i> <?php echo _l('New'); ?>
                            </button>
                        </div>


                        <table class="table table-bordered table-table_critical_tracker">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Department</th>
                                    <th>Area/Head</th>
                                    <th>Description</th>
                                    <th>Decision</th>
                                    <th>Action</th>
                                    <th>Action By</th>
                                    <th>Target Date</th>
                                    <th>Date Closed</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<div class="modal fade" id="addNewRowModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="width: 98%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('Add New'); ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <!-- <div class="col-md-8 pull-right">
                    <div class="col-md-2 pull-right">
                        <div id="dowload_file_sample" style="margin-top: 22px;">
                            <label for="file_csv" class="control-label"> </label>
                            <a href="<?php echo site_url('modules/purchase/uploads/file_sample/Sample_import_order_tracker_en.xlsx') ?>" class="btn btn-primary">Template</a>
                        </div>
                    </div>
                    <div class="col-md-4 pull-right" style="display: flex;align-items: end;padding: 0px;">
                        <?php echo form_open_multipart(admin_url('purchase/import_file_xlsx_order_tracker_items'), array('id' => 'import_form')); ?>
                        <?php echo form_hidden('leads_import', 'true'); ?>
                        <?php echo render_input('file_csv', 'choose_excel_file', '', 'file'); ?>

                        <div class="form-group" style="margin-left: 10px;">
                            <button id="uploadfile" type="button" class="btn btn-info import" onclick="return uploadfilecsv(this);"><?php echo _l('import'); ?></button>
                        </div>
                        <?php echo form_close(); ?>
                    </div>

                </div> -->
                <div class="col-md-12 ">
                    <div class="form-group pull-right" id="file_upload_response">

                    </div>

                </div>
                <div id="box-loading" class="pull-right">

                </div>
            </div>
            <div class="modal-body invoice-item">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive" style="overflow-x: unset !important;">
                            <?php
                            echo form_open_multipart('', array('id' => 'critical_tracker-form'));
                            ?>
                            <table class="table critical-tracker-items-table items table-main-invoice-edit has-calculations no-mtop">
                                <thead>
                                    <tr>
                                    <tr>
                                        <th>Department</th>
                                        <th>Area/Head</th>
                                        <th><strong>Description</strong></th>
                                        <th><strong>Decision</strong></th>
                                        <th><strong>Action</strong></th>
                                        <th><strong>Action By</strong></th>
                                        <th width="5%"><strong>Target Date</strong></th>
                                        <th width="5%"><strong>Date Closed</strong></th>
                                        <th width="5%"><strong>Status</strong></th>
                                        <th width="5%"><strong>Priority</strong></th>
                                        <th width="3%"></th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody class="mom_body">
                                    <?php echo pur_html_entity_decode($mom_row_template); ?>
                                </tbody>
                            </table>
                            <button type="submit" class="btn btn-info pull-right"><?php echo _l('Save'); ?></button>
                            </form>
                        </div>
                        <div id="removed-items"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<?php require 'modules/meeting_management/assets/js/critical_mom_js.php'; ?>
</body>

</html>

