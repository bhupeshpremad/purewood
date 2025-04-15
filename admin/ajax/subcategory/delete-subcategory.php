<?php
header('Content-Type: application/json');
include '../../../config/config.php';

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int) $_POST['id'];

    try {
        // First fetch the image
        $stmt = $conn->prepare("SELECT image FROM subcategories WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Delete image from folder
            $imagePath = '../../uploads/subcategory/' . $row['image'];
            if (!empty($row['image']) && file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete record from DB
            $delete = $conn->prepare("DELETE FROM subcategories WHERE id = ?");
            if ($delete->execute([$id])) {
                $response['success'] = true;
                $response['message'] = 'Subcategory deleted successfully';
            } else {
                $response['message'] = 'Failed to delete subcategory from database';
            }
        } else {
            $response['message'] = 'Subcategory not found';
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
    }
}

echo json_encode($response);
