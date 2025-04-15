<?php
include '../../../config/config.php';

$response = ['status' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);

    $stmt = $conn->prepare("UPDATE subsubcategories SET status = ?, updated_at = NOW() WHERE id = ?");
    $result = $stmt->execute([$status, $id]);

    if ($result) {
        $response = [
            'status' => true,
            'message' => 'Status updated successfully',
            'new_status' => $status
        ];
    } else {
        $response['message'] = 'Failed to update status';
    }
}

echo json_encode($response);
?>
