<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php init_head();  ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4><?php echo _l('meeting_critical_agenda'); ?></h4>



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
                            <tbody id="minutes-tbody">
                                <?php if (!empty($critical_agenda)) : ?>
                                    <?php $serial = 1;
                                    $departments_by_id = array_column($department, null, 'departmentid');
                                    $target_date = $date_closed = $status = ''; ?>
                                    <?php foreach ($critical_agenda as $key => $agenda) :

                                        if (!empty($agenda['target_date'])) {
                                            $target_date = date('d M, Y', strtotime($agenda['target_date']));
                                        } else {
                                            $target_date = '';
                                        }

                                        if (!empty($agenda['date_closed'])) {
                                            $date_closed = date('d M, Y', strtotime($agenda['date_closed']));
                                        } else {
                                            $date_closed = '';
                                        }


                                        $status_labels = [
                                            1 => ['label' => 'danger', 'table' => 'open', 'text' => _l('Open')],
                                            2 => ['label' => 'success', 'table' => 'close', 'text' => _l('close')],
                                        ];
                                        // Start generating the HTML
                                        $status_html = '';
                                        if (isset($status_labels[$agenda['status']])) {
                                            $status = $status_labels[$agenda['status']];
                                            $status_html = '<span class="inline-block label label-' . $status['label'] . '" id="status_span_' . $agenda['id'] . '" task-status-table="' . $status['table'] . '">' . $status['text'];
                                        }


                                        $status_html .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                                        $status_html .= '<a href="#" class="dropdown-toggle text-dark" id="tablePurOderStatus-' . $agenda['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                        $status_html .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
                                        $status_html .= '</a>';

                                        $status_html .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tablePurOderStatus-' . $agenda['id'] . '">';

                                        foreach ($status_labels as $key => $status) {
                                            if ($key != $agenda['status']) {
                                                $status_html .= '<li>
                                                     <a href="#" onclick="change_status_mom(' . $key . ', ' . $agenda['id'] . '); return false;">
                                                           ' . $status['text'] . '
                                                     </a>
                                                  </li>';
                                            }
                                        }

                                        $status_html .= '</ul>';
                                        $status_html .= '</div>';
                                        $status_html .= '</span>';

                                        $priority_labels = [
                                            1 => ['label' => 'warning', 'table' => 'high', 'text' => _l('High')],
                                            2 => ['label' => 'default', 'table' => 'low', 'text' => _l('Low')],
                                            3 => ['label' => 'info', 'table' => 'medium', 'text' => _l('Medium')],
                                            4 => ['label' => 'danger', 'table' => 'urgent', 'text' => _l('Urgent')],
                                        ];
                                        // Start generating the HTML
                                        $priority_html = '';
                                        if (isset($priority_labels[$agenda['priority']])) {
                                            $priority = $priority_labels[$agenda['priority']];
                                            $priority_html = '<span class="inline-block label label-' . $priority['label'] . '" id="priority_span_' . $agenda['id'] . '" task-status-table="' . $priority['table'] . '">' . $priority['text'];
                                        }


                                        $priority_html .= '<div class="dropdown inline-block mleft5 table-export-exclude">';
                                        $priority_html .= '<a href="#" class="dropdown-toggle text-dark" id="tablePurOderPriority-' . $agenda['id'] . '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                                        $priority_html .= '<span data-toggle="tooltip" title="' . _l('ticket_single_change_status') . '"><i class="fa fa-caret-down" aria-hidden="true"></i></span>';
                                        $priority_html .= '</a>';

                                        $priority_html .= '<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="tablePurOderPriority-' . $agenda['id'] . '">';

                                        foreach ($priority_labels as $key => $priority) {
                                            if ($key != $agenda['priority']) {
                                                $priority_html .= '<li>
                                                     <a href="#" onclick="change_priority_mom(' . $key . ', ' . $agenda['id'] . '); return false;">
                                                           ' . $priority['text'] . '
                                                     </a>
                                                  </li>';
                                            }
                                        }

                                        $priority_html .= '</ul>';
                                        $priority_html .= '</div>';
                                        $priority_html .= '</span>';


                                        // First, let's create a label configuration for departments
                                        $department_label_config = [
                                            'default' => ['label' => 'default', 'table' => 'department', 'text' => 'Department']
                                        ];

                                        // Start generating the HTML
                                        $department_html = '';
                                        if (isset($departments_by_id[$agenda['department']])) {
                                            $dept         = $departments_by_id[$agenda['department']];
                                            $label_config = $department_label_config['default'];

                                            $department_html  = '<span class="inline-block label label-'
                                                . $label_config['label']
                                                . '" id="department_span_'
                                                . $agenda['id']
                                                . '" task-status-table="'
                                                . $label_config['table']
                                                . '">'
                                                . $dept['name'];
                                        }
                                        // dropdown toggle
                                        $department_html .= '<div class="dropdown inline-block mleft5 table-export-exclude">'
                                            . '<a href="#" class="dropdown-toggle text-dark" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
                                            . '<span data-toggle="tooltip" title="' . _l('change_department') . '">'
                                            . '<i class="fa fa-caret-down"></i></span></a>';

                                        // menu items
                                        $department_html .= '<ul class="dropdown-menu dropdown-menu-right">';
                                        foreach ($departments_by_id as $id => $d) {
                                            if ($id != $agenda['department']) {
                                                $department_html .= '<li>'
                                                    . '<a href="#" onclick="change_department('
                                                    . $id . ', ' . $agenda['id']
                                                    . ');return false;">'
                                                    . $d['name']
                                                    . '</a></li>';
                                            }
                                        }
                                        $department_html .= '</ul></div></span>';


                                    ?>

                                        <tr>
                                            <td><?php echo $serial; ?></td>
                                            <td><?php echo $department_html; ?></td>
                                            <td><?php echo $agenda['area']; ?></td>
                                            <td><?php echo $agenda['description']; ?></td>
                                            <td><?php echo $agenda['decision']; ?></td>
                                            <td><?php echo $agenda['action']; ?></td>
                                            <td>
                                                <?php
                                                if ($agenda['staff'] > 0) {
                                                    echo getStaffNamesFromCSV($agenda['staff']) . '<br>';
                                                } ?>
                                                <?php echo $agenda['vendor']; ?>
                                            </td>
                                            <td><?php echo $target_date; ?></td>
                                            <td><?php echo '<input type="date" class="form-control closed-date-input" 
                        value="' . $agenda['date_closed'] . '" data-id="' . $agenda['id'] . '" ">'; ?></td>
                                            <td><?php echo $status_html; ?></td>
                                            <td><?php echo $priority_html; ?></td>
                                        </tr>
                                        <?php $serial++; ?>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="11" class="text-center"><?php echo _l('no_records_found'); ?></td>
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

<script>
    $(document).ready(function() {
        $('#project_filter').change(function() {
            const selectedProject = $(this).val();

            $.ajax({
                url: '<?php echo admin_url('meeting_management/agendaController/filter_minutes'); ?>',
                type: 'GET',
                data: {
                    project_filter: selectedProject
                },
                dataType: 'json',
                success: function(response) {
                    updateTableBody(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        function updateTableBody(data) {
            const tbody = $('table tbody');
            tbody.empty();

            if (data.length > 0) {
                $.each(data, function(index, agenda) {
                    const row = `
                    <tr>
                        <td>${agenda.meeting_title}</td>
                        <td>${agenda.project_name || 'N/A'}</td>
                        <td>${formatDate(agenda.meeting_date)}</td>
                        <td>
                            <a href="<?php echo admin_url('meeting_management/minutesController/index/'); ?>${agenda.id}" class="btn btn-primary"><?php echo _l('edit_converted_metting'); ?></a>
                            <a href="<?php echo admin_url('meeting_management/agendaController/delete/'); ?>${agenda.id}" class="btn btn-danger"><?php echo _l('delete'); ?></a>
                            <a href="<?php echo admin_url('meeting_management/agendaController/view_meeting/'); ?>${agenda.id}" class="btn btn-secondary"><?php echo _l('view_meeting'); ?></a>
                        </td>
                    </tr>
                `;
                    tbody.append(row);
                });
            } else {
                tbody.append('<tr><td colspan="4" class="text-center"><?php echo _l("no_agendas_found"); ?></td></tr>');
            }
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
    });

    function change_status_mom(status, id) {
        "use strict";
        if (id > 0) {
            $.post(admin_url + 'meeting_management/minutesController/change_status_mom/' + status + '/' + id)
                .done(function(response) {
                    try {
                        response = JSON.parse(response);

                        if (response.success) {
                            var $statusSpan = $('#status_span_' + id);

                            // Debugging
                            // console.log('Before:', $statusSpan.attr('class'));

                            // Remove all status-related classes
                            $statusSpan.removeClass('label-danger label-success label-info label-warning label-primary label-purple label-teal label-orange label-green label-defaul label-secondaryt');

                            // Add the new class and update content
                            if (response.class) {
                                $statusSpan.addClass(response.class);
                            }
                            if (response.status_str) {
                                $statusSpan.html(response.status_str + ' ' + (response.html || ''));
                            }

                            // Debugging
                            // console.log('After:', $statusSpan.attr('class'));

                            // Display success message
                            // $(".table-table_order_tracker").DataTable().ajax.reload();
                            alert_float('success', response.mess);
                        } else {
                            // Display warning message if the operation fails
                            alert_float('warning', response.mess);
                        }
                    } catch (e) {
                        console.error('Error parsing server response:', e);
                        alert_float('danger', 'Invalid server response');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert_float('danger', 'Failed to update status');
                });
        }
    }


    function change_priority_mom(status, id) {
        "use strict";
        if (id > 0) {
            $.post(admin_url + 'meeting_management/minutesController/change_priority_mom/' + status + '/' + id)
                .done(function(response) {
                    try {
                        response = JSON.parse(response);

                        if (response.success) {
                            var statusSpan = $('#priority_span_' + id);



                            // Remove all status-related classes
                            statusSpan.removeClass('label-danger label-success label-info label-warning label-primary label-purple label-teal label-orange label-green label-defaul label-secondaryt');

                            // Add the new class and update content
                            if (response.class) {
                                statusSpan.addClass(response.class);
                            }
                            if (response.priority_str) {
                                statusSpan.html(response.priority_str + ' ' + (response.html || ''));
                            }



                            // Display success message
                            // $(".table-table_order_tracker").DataTable().ajax.reload();
                            alert_float('success', response.mess);
                        } else {
                            // Display warning message if the operation fails
                            alert_float('warning', response.mess);
                        }
                    } catch (e) {
                        console.error('Error parsing server response:', e);
                        alert_float('danger', 'Invalid server response');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert_float('danger', 'Failed to update status');
                });
        }
    }

    function change_department(departmentId, agendaId) {
        "use strict";
        if (agendaId > 0) {
            $.post(admin_url + 'meeting_management/minutesController/change_department/' + departmentId + '/' + agendaId)
                .done(function(response) {
                    try {
                        response = JSON.parse(response);

                        if (response.success) {
                            var deptSpan = $('#department_span_' + agendaId);

                            // Update the department name
                            if (response.department_name) {
                                // Remove the dropdown and keep just the department name
                                deptSpan.html(response.department_name);

                                // Re-add the dropdown HTML
                                deptSpan.append(response.html);
                            }

                            alert_float('success', response.message);
                        } else {
                            alert_float('warning', response.message);
                        }
                    } catch (e) {
                        console.error('Error parsing server response:', e);
                        alert_float('danger', 'Invalid server response');
                    }
                })
                .fail(function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert_float('danger', 'Failed to update department');
                });
        }
    }
    var table_critical_tracker = $('.table-table_critical_tracker');
    $('body').on('change', '.closed-date-input', function(e) {
            e.preventDefault();

            var rowId = $(this).data('id');
            var closedDate = $(this).val();

            // Perform AJAX request to update the completion date
            $.post(admin_url + 'meeting_management/minutesController/update_closed_date', {
                id: rowId,
                closedDate: closedDate
            }).done(function(response) {
                response = JSON.parse(response);
                if (response.success) {
                    alert_float('success', response.message);
                    table_critical_tracker.reload(null, false); // Reload table without refreshing the page
                } else {
                    alert_float('danger', response.message);
                }
            });
        });
</script>