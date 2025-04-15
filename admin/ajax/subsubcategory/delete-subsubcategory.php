<?php
include '../../../config/config.php';

$response = ['status' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Optional: check if subsubcategory exists
    $stmt = $conn->prepare("SELECT image FROM subsubcategories WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        // Delete image from folder (if exists)
        if (!empty($data['image']) && file_exists('../../../' . $data['image'])) {
            unlink('../../../' . $data['image']);
        }

        // Delete from database
        $stmt = $conn->prepare("DELETE FROM subsubcategories WHERE id = ?");
        $result = $stmt->execute([$id]);

        $response = [
            'status' => $result,
            'message' => $result ? 'Sub-Subcategory deleted successfully' : 'Failed to delete'
        ];
    } else {
        $response['message'] = 'Sub-Subcategory not found';
    }
}

echo json_encode($response);
?>
