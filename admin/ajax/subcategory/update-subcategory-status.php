<?php
header('Content-Type: application/json');
include('../../../config/config.php');

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];
    $status = isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;

    try {
        $stmt = $conn->prepare("UPDATE subcategories SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);

        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = $status ? 'Subcategory activated successfully' : 'Subcategory deactivated successfully';
        } else {
            $response['message'] = 'No changes made or subcategory not found';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}

echo json_encode($response);
