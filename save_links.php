<?php
$data = json_decode(file_get_contents('php://input'), true);
if ($data && isset($data['Links'])) {
    file_put_contents('links.json', json_encode($data, JSON_PRETTY_PRINT));
    echo 'Links saved successfully!';
} else {
    echo 'Failed to save links.';
}
?>
