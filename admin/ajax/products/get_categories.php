<?php
include '../../../config/config.php';

$sql = "SELECT id, name FROM categories WHERE status = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($categories);
?>