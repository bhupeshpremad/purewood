<?php
include '../../../config/config.php';

if (isset($_POST['subcategory_id']) && is_numeric($_POST['subcategory_id'])) {
    $subcategory_id = intval($_POST['subcategory_id']);
    $sql = "SELECT id, name FROM subsubcategories WHERE subcategory_id = :subcategory_id AND status = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':subcategory_id', $subcategory_id, PDO::PARAM_INT);
    $stmt->execute();
    $subsubcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($subsubcategories);
} else {
    echo json_encode([]);
}
?>