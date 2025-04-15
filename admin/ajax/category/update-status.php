<?php
include('../../../config/config.php');

$response = ['success' => false, 'message' => ''];

$id = $_POST['id'] ?? '';
$status = $_POST['status'] ?? 0;

if (!$id) {
    $response['message'] = "Invalid ID";
    echo json_encode($response);
    exit;
}

$stmt = $conn->prepare("UPDATE categories SET status = ? WHERE id = ?");
if ($stmt->execute([$status, $id])) {
    $response['success'] = true;
    $response['message'] = $status == 1 ? "Category activated" : "Category deactivated";
} else {
    $response['message'] = "Failed to update status";
}

echo json_encode($response);
