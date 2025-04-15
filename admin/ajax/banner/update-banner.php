<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../../../config/config.php';
header('Content-Type: application/json');

function returnJson($data) {
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['id']) && isset($_POST['status']) && !isset($_POST['name'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE banners SET status = ? WHERE id = ?");
        if ($stmt->execute([$status, $id])) {
            returnJson([
                'success' => true,
                'message' => $status == 1 ? "Banner Activated Successfully" : "Banner Deactivated Successfully"
            ]);
        } else {
            returnJson([
                'success' => false,
                'message' => "Failed to update status: " . $stmt->errorInfo()[2]
            ]);
        }
    } else {
        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $alt_tag = trim($_POST['alt_tag']);
        $status = isset($_POST['status']) ? 1 : 0;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array(strtolower($ext), $allowedExtensions)) {
                returnJson(['status' => false, 'message' => 'Invalid image format. Allowed formats: jpg, jpeg, png, gif']);
            }
            $fileName = 'banner_' . time() . '.' . $ext;
            $uploadDir = '../../uploads/banners/';
            $uploadPath = $uploadDir . $fileName;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                returnJson(['status' => false, 'message' => 'Image upload failed.']);
            }

            $stmt = $conn->prepare("UPDATE banners SET name = ?, alt_tag = ?, image = ?, status = ? WHERE id = ?");
            $result = $stmt->execute([$name, $alt_tag, $fileName, $status, $id]);
        } else {
            $stmt = $conn->prepare("UPDATE banners SET name = ?, alt_tag = ?, status = ? WHERE id = ?");
            $result = $stmt->execute([$name, $alt_tag, $status, $id]);
        }

        if ($result) {
            returnJson(['status' => true, 'message' => 'Banner updated successfully.']);
        } else {
            returnJson(['status' => false, 'message' => 'Failed to update banner: ' . $stmt->errorInfo()[2]]);
        }
    }
}

returnJson(['status' => false, 'message' => 'Invalid request']);
?>