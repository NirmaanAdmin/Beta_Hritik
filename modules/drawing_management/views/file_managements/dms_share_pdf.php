<?php defined('BASEPATH') or exit('No direct script access allowed');
// These lines should always be at the end of the document, flush left. Don't indent these lines.
$html = <<<EOF
<div class="div_pdf">
$transmittal
</div>
EOF;

$pdf->writeHTML($html, true, false, true, false, '');

