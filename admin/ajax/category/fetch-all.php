<?php
include('../../../config/config.php');

$stmt = $conn->prepare("SELECT * FROM categories ORDER BY id DESC");
$stmt->execute();

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($categories);
