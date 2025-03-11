<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Check if URL is provided in the request
    if (isset($_POST['filepath'])) {
        // Get the file path directly (not a URL)
        $filePath = $_POST['filepath'];
        $name = $_POST['name'];
        $folder = $_POST['folder'];
        $parentId = $_POST['parentId'];

        // Sanitize the path to remove any URL components or encoding issues
        $filePath = urldecode($filePath);
        // Remove any potential URL prefix
        $filePath = 'modules/drawing_management/uploads/'.$folder.'/'.$parentId.'/'.$name;
        // Replace any encoded spaces with actual spaces
        /*$filePath = str_replace('%20', ' ', $filePath);*/

        // Log the path for debugging
        error_log("Attempting to save to path: " . $filePath);

        // If path doesn't exist, create directories
        $dirPath = dirname($filePath);
        if (!file_exists($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        // Now move the uploaded file to this path
        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            echo json_encode(['status' => 'success', 'message' => 'File saved successfully']);
        } else {
            $error = error_get_last();
            echo json_encode([
                'status' => 'error',
                'message' => 'File save failed',
                'path' => $filePath,
                'error' => $error ? $error['message'] : 'Unknown error'
            ]);
        }
    } else {
        // If no file path provided, use a default upload directory
        $uploadDir = 'modules/drawing_management/uploads/edited/';

        // Create directory if it doesn't exist
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = $_FILES['file']['name'];
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $filePath)) {
            echo json_encode(['status' => 'success', 'message' => 'File saved successfully', 'path' => $filePath]);
        } else {
            $error = error_get_last();
            echo json_encode([
                'status' => 'error',
                'message' => 'File save failed',
                'path' => $filePath,
                'error' => $error ? $error['message'] : 'Unknown error'
            ]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No file received']);
}
?>
