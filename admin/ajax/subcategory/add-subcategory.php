<?php
header('Content-Type: application/json');
include('../../../config/config.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Slug converter function
function generateSlug($string) {
    $slug = strtolower($string);
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

try {
    $category_id = $_POST['category_id'] ?? '';
    $name        = $_POST['name'] ?? '';
    $rawSlug     = $_POST['slug'] ?? '';
    $status      = isset($_POST['status']) ? 1 : 0;
    $imageName   = '';

    // Slug sanitization
    $slug = generateSlug(!empty($rawSlug) ? $rawSlug : $name);

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        $targetPath = '../../uploads/subcategory/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    // Check if ID is present for update
    if (!empty($_POST['id'])) {
        $id = $_POST['id'];

        // If image not uploaded, fetch old image from DB
        if (!$imageName) {
            $stmt = $conn->prepare("SELECT image FROM subcategories WHERE id = ?");
            $stmt->execute([$id]);
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);
            $imageName = $existing['image'] ?? '';
        }

        // Update query (always include image)
        $sql = "UPDATE subcategories SET category_id = ?, name = ?, slug = ?, image = ?, status = ? WHERE id = ?";
        $params = [$category_id, $name, $slug, $imageName, $status, $id];

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['success' => true, 'message' => 'Subcategory updated successfully']);
    } else {
        // Insert new subcategory
        $stmt = $conn->prepare("INSERT INTO subcategories (category_id, name, slug, image, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$category_id, $name, $slug, $imageName, $status]);

        echo json_encode(['success' => true, 'message' => 'Subcategory added successfully']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
