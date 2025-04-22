<style type="text/css">
    .daily_report_title,
    .daily_report_activity {
        font-weight: bold;
        text-align: center;
        background-color: lightgrey;
    }

    .daily_report_title {
        font-size: 17px;
    }

    .daily_report_activity {
        font-size: 16px;
    }

    .daily_report_head {
        font-size: 14px;
    }

    .daily_report_label {
        font-weight: bold;
    }

    .daily_center {
        text-align: center;
    }

    .table-responsive {
        overflow-x: visible !important;
        scrollbar-width: none !important;
    }

    .laber-type .dropdown-menu .open,
    .agency .dropdown-menu .open {
        width: max-content !important;
    }

    .agency .dropdown-toggle,
    .laber-type .dropdown-toggle {
        width: 90px !important;
    }
</style>
<div class="col-md-12">
    <hr class="hr-panel-separator" />
</div>

<div class="col-md-12">
    <div class="table-responsive">
        <table class="table dpr-items-table items table-main-dpr-edit has-calculations no-mtop">

            <thead>
                <tr>
                    <th colspan="9" class="daily_report_title">Quality Observation Report</th>
                </tr>
                <tr>
                    <?php
                    $user = get_staff_user_id();
                    $where = 'staffid = ' . $user;
                    $get_login_user_name =  get_staff_list($where);
                    ?>
                    <th colspan="2" class="daily_report_head">
                        <span class="daily_report_label" style="display: ruby;">Raised by : <?= $get_login_user_name[0]['name'] ?></span>
                    </th>
                    <th colspan="2" class="daily_report_head">
                        <span class="daily_report_label" style="display: flex;align-items: baseline;">Issue Date:
                            <div class="form-group" style="margin-left: 13px;">
                                <input type="date" class="form-control" name="issue_date" value="<?= isset($msh_form->date) ? date('Y-m-d\TH:i', strtotime($msh_form->date)) : '' ?>">
                            </div>
                        </span>
                    </th>
                    <th colspan="2" class="daily_report_head">
                        <span class="daily_report_label" style="display: flex;align-items: baseline;">Observation No.:
                            <div class="form-group" style="margin-left: 13px;">
                                <input type="text" class="form-control" name="observation_no" value="<?= isset($msh_form->observation_no) ? $msh_form->observation_no : '' ?>">
                            </div>
                        </span>
                    </th>
                </tr>

                <tr>
                    <th colspan="4" class="daily_report_head">
                        <span class="daily_report_label" style="display: ruby;">Material or Works Involved : <?php echo render_input('material_or_works_involved', '', isset($msh_form->material_or_works_involved) ? $msh_form->material_or_works_involved : '', 'text'); ?></span>

                    </th>
                    <th colspan="4" class="daily_report_head">
                        <?php $vendor_list = get_vendor_list_for_forms(); ?>
                        <span class="daily_report_label" style="display: ruby;">Supplier/Contractor in Charge: <?php echo render_select('supplier_contractor_in_charge', $vendor_list, array('userid', 'company'), '', isset($msh_form->supplier_contractor_in_charge) ? $msh_form->supplier_contractor_in_charge : ''); ?></span>
                    </th>
                </tr>
                <tr>
                    <th colspan="4" class="daily_report_head">
                        <span class="daily_report_label" style="display: ruby;">Specification/Drawing Reference : <?php echo render_input('specification_drawing_reference', '', isset($msh_form->specification_drawing_reference) ? $msh_form->specification_drawing_reference : '', 'text'); ?></span>

                    </th>
                    <th colspan="4" class="daily_report_head">
                        <span class="daily_report_label" style="display: ruby;">Procedure or ITP Reference: <?php echo render_input('procedure_or_itp_reference', '', isset($msh_form->procedure_or_itp_reference) ? $msh_form->procedure_or_itp_reference : '', 'text'); ?></span>
                    </th>
                </tr>
                <tr>
                    <th colspan="8" class="daily_report_head">
                        <span class="daily_report_label" style="display: ruby;">Location : <span class="view_project_name"></span></span>
                    </th>

                </tr>

                <tr>
                    <th colspan="8" class="daily_report_head">
                        <div class="daily_report_label col-md-12" style="display: flex;padding:0px;">
                            <div class="col-md-1" style="padding: 0px;">
                                Observation Description:
                            </div>
                            <div class="col-md-11" style="padding: 0px;">
                                <input
                                    type="text"
                                    name="observation_description"
                                    value="<?php echo isset($msh_form->observation_description) ? htmlspecialchars($msh_form->observation_description) : ''; ?>"
                                    class="form-control" />
                            </div>
                        </div>

                    </th>
                </tr>
                <tr>
                    <th colspan="4" class="daily_report_head">
                        <div class="daily_report_label col-md-12" style="display: flex; padding: 0px;">
                            <div class="col-md-2" style="padding: 0px;">
                                Design Consultant Recommendation:
                            </div>
                            <div class="col-md-10" style="padding: 0px;">
                                <input
                                    type="text"
                                    name="design_consultant_recommendation"
                                    value="<?php echo isset($msh_form->design_consultant_recommendation) ? htmlspecialchars($msh_form->design_consultant_recommendation) : ''; ?>"
                                    class="form-control" />
                            </div>
                        </div>


                    </th>
                    <th colspan="4" class="daily_report_head">
                        <span class="daily_report_label" style="display: flex;align-items: baseline;">Ref. & Date :
                            <div class="form-group">
                                <input type="date" class="form-control" name="ref_date1" value="<?= isset($msh_form->ref_date1) ? date('Y-m-d\TH:i', strtotime($msh_form->ref_date1)) : '' ?>">
                            </div>
                        </span>
                    </th>
                </tr>
                <tr>
                    <th colspan="4" class="daily_report_head">
                        <div class="daily_report_label col-md-12" style="display: flex; padding: 0px;">
                            <div class="col-md-2" style="padding: 0px;">
                                Client Instruction:
                            </div>
                            <div class="col-md-10" style="padding: 0px;">
                                <input
                                    type="text"
                                    name="client_instruction"
                                    value="<?php echo isset($msh_form->client_instruction) ? htmlspecialchars($msh_form->client_instruction) : ''; ?>"
                                    class="form-control" />
                            </div>
                        </div>


                    </th>
                    <th colspan="4" class="daily_report_head">
                        <span class="daily_report_label" style="display: flex;align-items: baseline;">Ref. & Date :
                            <div class="form-group">
                                <input type="date" class="form-control" name="ref_date2" value="<?= isset($msh_form->ref_date2) ? date('Y-m-d\TH:i', strtotime($msh_form->ref_date2)) : '' ?>">
                            </div>
                        </span>
                    </th>
                </tr>
                <tr>
                    <th colspan="1" class="daily_report_head">
                        <span class="daily_report_label" style="display: ruby;">Supplier/Contractor’s
                            Proposed Corrective Action : </span>

                    </th>
                    <th colspan="2" class="daily_report_head">
                        <span class="daily_report_label" style="display: ruby;">Immediate Action : 
                        <?php echo render_input('suppliers_proposed_corrective_action1', '', isset($msh_form->suppliers_proposed_corrective_action1) ? $msh_form->suppliers_proposed_corrective_action1 : '', 'text'); ?></span>

                    </th>
                    <th colspan="2" class="daily_report_head">
                        <span class="daily_report_label" style="display: ruby;">Measure to prevent recurrence:  <?php echo render_input('suppliers_proposed_corrective_action2', '', isset($msh_form->suppliers_proposed_corrective_action2) ? $msh_form->suppliers_proposed_corrective_action2 : '', 'text'); ?></span>

                    </th>
                    <th colspan="2" class="daily_report_head">
                        <span class="daily_report_label" style="display: flex;align-items: baseline;">Date:
                            <div class="form-group">
                                <input type="date" class="form-control" name="proposed_date" value="<?= isset($msh_form->proposed_date) ? date('Y-m-d\TH:i', strtotime($msh_form->proposed_date)) : '' ?>">
                            </div>
                        </span>
                    </th>
                </tr>

            </thead>


            <tbody>


            </tbody>
        </table>
        <div class="col-md-12 display-flex">
            <label>
                <input type="checkbox" name="approval" value="proceed" class="single-checkbox">
                Approved to Proceed
            </label>
            <br>
            <label style="margin-left: 2%;">
                <input type="checkbox" name="approval" value="proceed_comments" class="single-checkbox">
                Approved to Proceed with Comments
            </label>
            <br>
            <label style="margin-left: 2%;">
                <input type="checkbox" name="approval" value="not_approved" class="single-checkbox">
                Not Approved
            </label>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <?php echo render_textarea('comments', 'Comments', isset($msh_form) ? $msh_form->comments : '',); ?>
            </div>
        </div>

    </div>
    <table class="table dpr-items-table items table-main-dpr-edit has-calculations no-mtop">
        <thead>
            <tr>
                <th colspan="4" class="daily_report_head">
                    <span class="daily_report_label" style="display: ruby;">Name : <?php echo render_select('staff_name', get_staff_list(), array('staffid', 'name'), '', isset($apc_form->staff_name) ? $apc_form->staff_name : ''); ?></span>

                </th>
                <th colspan="4" class="daily_report_head">
                    <span class="daily_report_label" style="display: flex;align-items: baseline;">Date :
                        <div class="form-group">
                            <input type="date" class="form-control" name="staff_name_date" value="<?= isset($msh_form->staff_name_date) ? date('Y-m-d', strtotime($msh_form->staff_name_date)) : '' ?>">
                        </div>
                    </span>
                </th>
            </tr>
            <tr>
                <th colspan="2" class="daily_report_head">
                    <span class="daily_report_label" style="display: ruby;">Observation Close-Out :
                        <label>
                            <input type="checkbox" name="close_out" value="corrective_action" class="single-checkbox1">
                            Corrective Action Accepted
                        </label>

                        <label style="margin-left: 2%;">
                            <input type="checkbox" name="close_out" value="corrective_action_not_accepted" class="single-checkbox1">
                            Corrective Action Not Accepted
                        </label>
                    </span>

                </th>
                <th colspan="2" class="daily_report_head">
                    <span class="daily_report_label" style="display: flex;align-items: baseline;">Date :
                        <div class="form-group">
                            <input type="date" class="form-control" name="observation_date" value="<?= isset($msh_form->observation_date) ? date('Y-m-d', strtotime($msh_form->observation_date)) : '' ?>">
                        </div>
                    </span>
                </th>
                <th colspan="2" class="daily_report_head">
                    <span class="daily_report_label" style="display: flex;align-items: baseline;">Comments :
                        <div class="form-group">
                            <?php echo render_textarea('comments1', '', isset($msh_form) ? $msh_form->comments1 : '',); ?>
                        </div>
                    </span>
                </th>
            </tr>
        </thead>
    </table>
    <div class="table-responsive">
        <div id="sectionsContainer"></div>

        <button type="button" id="addSectionBtn" class="btn pull-right btn-info">Add</button>
    </div>
</div>

<template id="sectionTemplate">
    <div class="section">
        <h4>Section <span class="secIndex"></span>
            <a href="javascript:void(0);" class="btn btn-danger removeSection pull-right" style="margin-bottom:10px;"><i class="fa fa-trash"></i></a>
        </h4>
        <table class="table qor-items-table items table-main-qor-edit has-calculations no-mtop">
            <thead>
                <tr>
                    <th class="daily_report_head daily_center">Comments</th>
                    <th class="daily_report_head daily_center">Attachments</th>
                </tr>
            </thead>
            <tbody>
                <tr class="commentRow">
                    <td>
                        <textarea name="" class="commentInput form-control" required></textarea>
                    </td>
                    <td>
                        <div class="attachmentsList">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="input-group" style="width: 50%;margin-bottom: 10px;">
                                        <input type="file"
                                            extension="<?php echo str_replace(['.', ' '], '', get_option('form_attachments_file_extensions')); ?>"
                                            filesize="<?php echo file_upload_max_size(); ?>"
                                            class="form-control"
                                            accept="<?php echo get_form_form_accepted_mimes(); ?>">
                                        <span class="input-group-btn">
                                            <button type="button" class="addAttachmentBtn btn btn-default"><i class="fa fa-plus"></i></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </td>
                </tr>
            </tbody>
        </table>
        <hr />
    </div>
</template>

<script type="text/javascript">
    $(function() {
        let sectionCount = 0;

        function refreshIndices() {
            // update headings and input names
            $('#sectionsContainer .section').each(function(i) {
                const $sec = $(this);
                $sec.find('.secIndex').text(i + 1);
                $sec.find('.commentInput')
                    .attr('name', `comments[${i}]`);
                // for each attachment input in this section:
                $sec.find('.attachmentsList input[type=file]')
                    .each(function(j) {
                        $(this).attr('name', `attachments[${i}][]`);
                    });
            });
        }

        // Add a whole new section
        $('#addSectionBtn').click(function() {
            sectionCount++;
            const $tpl = $($('#sectionTemplate').html());
            $('#sectionsContainer').append($tpl);
            refreshIndices();
        });

        // Remove a section
        $('#sectionsContainer').on('click', '.removeSection', function() {
            $(this).closest('.section').remove();
            refreshIndices();
        });

        // Add attachment: clone the input‐group and convert its button into “remove”
        $('#sectionsContainer').on('click', '.addAttachmentBtn', function() {
            const $grp = $(this).closest('.input-group');
            const $clone = $grp.clone();

            // Clear the cloned file‐input
            $clone.find('input[type=file]').val('');

            // Turn the “+” into a red “−” remove button
            $clone.find('button')
                .removeClass('addAttachmentBtn btn-default')
                .addClass('removeAttachmentBtn btn-danger')
                .html('<i class="fa fa-minus"></i>');

            // Insert it after the original row
            $grp.after($clone);

            refreshIndices();
        });

        // Remove an attachment row
        $('#sectionsContainer').on('click', '.removeAttachmentBtn', function() {
            $(this).closest('.input-group').remove();
            refreshIndices();
        });

        // Bootstrap with one section on load
        $('#addSectionBtn').trigger('click');
    });


    $('.single-checkbox').on('change', function() {
        if (this.checked) {
            $('.single-checkbox').not(this).prop('checked', false);
        }
    });
    $('.single-checkbox1').on('change', function() {
        if (this.checked) {
            $('.single-checkbox1').not(this).prop('checked', false);
        }
    });


    $('#project_id').on('change', function() {
        // var project_id = $(this).val();
        var project_name = $('#project_id option:selected').text();
        $('.view_project_name').html(project_name);
    });


    $(document).ready(function() {
        $('input.number').keypress(function(e) {
            var code = e.which || e.keyCode;

            // Allow backspace, tab, delete, and '/'
            if (code === 8 || code === 9 || code === 46 || code === 47) {
                return true;
            }

            // Allow letters (A-Z, a-z) and numbers (0-9)
            if (
                (code >= 48 && code <= 57) || // Numbers 0-9
                (code >= 65 && code <= 90) || // Uppercase A-Z
                (code >= 97 && code <= 122) // Lowercase a-z
            ) {
                return true;
            }

            // Block all other characters
            return false;
        });
    });
</script>