<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!-- Include Perfex CRM Header -->
<?php init_head(); ?>

<h4><?php echo _l('meeting_attendance'); ?></h4>

<p><?php echo _l('meeting_title') . ': ' . $agenda->meeting_title; ?></p>
<p><?php echo _l('meeting_date') . ': ' . $agenda->meeting_date; ?></p>

<div class="signature-container">
    <canvas id="signature-pad" class="signature-pad"></canvas>
    <button id="clear-signature" class="btn btn-warning"><?php echo _l('clear'); ?></button>
    <button id="save-signature" class="btn btn-success"><?php echo _l('save'); ?></button>
</div>

<script>
    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    document.getElementById('clear-signature').addEventListener('click', function () {
        signaturePad.clear();
    });

    document.getElementById('save-signature').addEventListener('click', function () {
        if (!signaturePad.isEmpty()) {
            var signatureData = signaturePad.toDataURL('image/png');
            $.post('<?php echo admin_url('attendanceController/save_signature'); ?>', {
                agenda_id: <?php echo $agenda->id; ?>,
                signature: signatureData
            }, function (response) {
                var res = JSON.parse(response);
                alert(res.message);
            });
        } else {
            alert('<?php echo _l('meeting_no_signature'); ?>');
        }
    });
</script>

<!-- Include Perfex CRM Footer -->
<?php init_tail(); ?>
