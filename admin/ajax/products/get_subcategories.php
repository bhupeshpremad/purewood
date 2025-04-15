<?php
include '../../../config/config.php';

if (isset($_POST['category_id']) && is_numeric($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);

    $sql = "SELECT id, name FROM subcategories WHERE category_id = :category_id AND status = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
    $stmt->execute();
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subcategories);
} else {
    echo json_encode([]);
}
?>