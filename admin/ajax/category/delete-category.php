<?php
include '../../../config/config.php';

$response = ['success' => false, 'message' => 'Something went wrong'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // First fetch the image to delete it from folder
    $stmt = $conn->prepare("SELECT image FROM categories WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Delete image from folder
        $imagePath = '../../uploads/category/' . $row['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Now delete from DB
        $delete = $conn->prepare("DELETE FROM categories WHERE id = ?");
        if ($delete->execute([$id])) {
            $response['success'] = true;
            $response['message'] = 'Category deleted successfully';
        } else {
            $response['message'] = 'Database delete failed';
        }
    } else {
        $response['message'] = 'Category not found';
    }
}

echo json_encode($response);
