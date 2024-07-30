<?php
if (!file_exists('icons')) {
    mkdir('icons', 0777, true);
}

$targetDir = "icons/";
$targetFile = $targetDir . basename($_FILES["icon_file"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["icon_file"]["tmp_name"]);
if ($check !== false) {
    $uploadOk = 1;
} else {
    $uploadOk = 0;
}

// Check if file already exists
if (file_exists($targetFile)) {
    $uploadOk = 0;
}

// Check file size
if ($_FILES["icon_file"]["size"] > 500000) {
    $uploadOk = 0;
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo json_encode(['success' => false]);
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["icon_file"]["tmp_name"], $targetFile)) {
        echo json_encode(['success' => true, 'filename' => basename($_FILES["icon_file"]["name"])]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
