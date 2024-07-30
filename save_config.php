<?php
// Read the posted JSON data
$configData = json_decode(file_get_contents('php://input'), true);

// Generate the config.php content
$configContent = "<?php\n";
foreach ($configData as $key => $value) {
    $configContent .= "\$$key = \"" . addslashes($value) . "\";\n";
}

// Save the config.php file
file_put_contents('config.php', $configContent);
echo json_encode(['success' => true]);
?>
