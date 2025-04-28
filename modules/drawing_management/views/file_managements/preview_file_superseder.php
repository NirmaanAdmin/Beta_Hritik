<?php
use setasign\Fpdi\Fpdi;

$folder = 'files';
$path = DRAWING_MANAGEMENT_MODULE_UPLOAD_FOLDER . '/' . $folder . '/' . $file->parent_id . '/' . $file->name;

if (is_image($path)) { ?>
    <img src="<?php echo base_url(DRAWING_MANAGEMENT_PATH . $folder . '/' . $file->parent_id . '/' . $file->name); ?>" class="img img-responsive img_style">
<?php } elseif (!empty($file->external) && !empty($file->thumbnail_link)) { ?>
    <img src="<?php echo optimize_dropbox_thumbnail($file->thumbnail_link); ?>" class="img img-responsive">
<?php } elseif (strpos($file->name, '.pdf') !== false && empty($file->external)) { ?>

    <?php
    $route = admin_url('drawing_management') . '?id=' . $file->id;
    $tokenName = $this->security->get_csrf_token_name();
    $token = $this->security->get_csrf_hash();

    // Load required libraries
    require_once(APPPATH.'third_party/fpdf/fpdf.php');
    require_once(APPPATH.'third_party/fpdi/autoload.php');

    // Function to stamp footer
    function stampFooter($srcPath, $destPath) {
        $pdf = new Fpdi();
        try {
            $pageCount = $pdf->setSourceFile($srcPath);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($tplId);

                // Create page with same size/orientation as template
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplId);

                // Footer image path
                $footerImg = FCPATH.'assets/images/pdf-footer-logo.png';
                if (file_exists($footerImg)) {
                    // Set width and height (small and proportional)
                    $targetWidth = 50; // 50 mm width
                    list($imgWidth, $imgHeight) = getimagesize($footerImg);

                    $ratio = $imgHeight / $imgWidth;
                    $targetHeight = $targetWidth * $ratio;

                    // Center horizontally
                    $x = ($size['width'] - $targetWidth) / 2;
                    // Position slightly above bottom
                    $y = $size['height'] - $targetHeight - 10;

                    // Add the image
                    $pdf->Image($footerImg, $x, $y, $targetWidth, $targetHeight);
                }
            }

            // Save the stamped PDF
            $pdf->Output($destPath, 'F');
            return true;
        } catch (Exception $e) {
            log_message('error', 'PDF stamping failed: ' . $e->getMessage());
            return false;
        }
    }

    // Paths for source and temp destination
    $src = DRAWING_MANAGEMENT_MODULE_UPLOAD_FOLDER.'/'.$folder.'/'.$file->parent_id.'/'.$file->name;
    $tempStamped = DRAWING_MANAGEMENT_MODULE_UPLOAD_FOLDER.'/'.$folder.'/'.$file->parent_id.'/temp_'.$file->name;

    // Stamp to a temporary file
    if (stampFooter($src, $tempStamped)) {
        // Replace original file safely
        if (file_exists($tempStamped)) {
            unlink($src); // Delete the original
            rename($tempStamped, $src); // Rename temp to original
        }
    }

    // Display updated original file
    $url = base_url(DRAWING_MANAGEMENT_PATH . $folder . '/' . $file->parent_id . '/' . $file->name);
    ?>

    <iframe src="<?= base_url('pdfjs/web/viewer.html?file=' . $url . '&name=' . $file->name . '&folder=' . $folder . '&parent_id=' . $file->parent_id) . '&back_route=' . $route . '&token_name=' . $tokenName . '&csrf_token=' . $token . '&base_url=' . base_url() ?>" width="100%" height="100%"></iframe>

<?php } else {
    echo '<p class="text-muted">' . _l('no_preview_available_for_file') . '</p>';
} ?>
