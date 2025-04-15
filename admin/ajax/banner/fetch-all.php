<?php
include '../../../config/config.php';

header('Content-Type: application/json');

$response = [];

try {
    $stmt = $conn->prepare("SELECT id, name, alt_tag, image, status FROM banners");
    $stmt->execute();
    $banners = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $response = $banners;
} catch (PDOException $e) {
    $response = ['error' => 'Failed to fetch banners: ' . $e->getMessage()];
    http_response_code(500); // Set HTTP status code to indicate an error
}

echo json_encode($response);
?>