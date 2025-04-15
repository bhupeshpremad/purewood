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
    $name = trim($_POST['name']);
    $alt_tag = trim($_POST['alt_tag']);
    $status = isset($_POST['status']) ? 1 : 0;

    if (empty($name)) {
        returnJson(['status' => false, 'message' => 'Banner Name is required.']);
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
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

        $stmt = $conn->prepare("INSERT INTO banners (name, alt_tag, image, status) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $alt_tag, $fileName, $status])) {
            returnJson(['status' => true, 'message' => 'Banner added successfully.']);
        } else {
            returnJson(['status' => false, 'message' => 'DB insertion failed: ' . $stmt->errorInfo()[2]]);
        }
    } else {
        returnJson(['status' => false, 'message' => 'Please upload a banner image.']);
    }
} else {
    returnJson(['status' => false, 'message' => 'Invalid request method.']);
}

returnJson(['status' => false, 'message' => 'Something went wrong.']);
?>