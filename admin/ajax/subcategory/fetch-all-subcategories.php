<?php
include('../../../config/config.php');

$stmt = $conn->prepare("SELECT s.*, c.name AS category_name FROM subcategories s 
                        LEFT JOIN categories c ON s.category_id = c.id
                        ORDER BY s.id DESC");
$stmt->execute();

$subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($subcategories);
