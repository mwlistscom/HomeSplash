<?php
$response = ['success' => false, 'filename' => ''];

if (isset($_FILES['icon_file']) && $_FILES['icon_file']['error'] == 0) {
    $targetDir = "icons/";
    
    // Create the icons directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    $fileName = basename($_FILES['icon_file']['name']);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    // Limit file size to 5MB
    $maxFileSize = 5 * 1024 * 1024; // 5MB

    // Check if the file is an allowed image type
    $check = getimagesize($_FILES['icon_file']['tmp_name']);
    if ($check !== false && in_array($imageFileType, $allowedTypes) && $_FILES['icon_file']['size'] <= $maxFileSize) {
        // Generate a unique file name if a file with the same name exists
        $uniqueFileName = $fileName;
        $counter = 1;
        while (file_exists($targetDir . $uniqueFileName)) {
            $uniqueFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . $counter . '.' . $imageFileType;
            $counter++;
        }
        $targetFile = $targetDir . $uniqueFileName;

        if (move_uploaded_file($_FILES['icon_file']['tmp_name'], $targetFile)) {
            $response['success'] = true;
            $response['filename'] = $uniqueFileName;
        } else {
            $response['error'] = 'Error uploading the file.';
        }
    } else {
        $response['error'] = 'Invalid file type or file too large.';
    }
} else {
    $response['error'] = 'No file uploaded or upload error.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>

