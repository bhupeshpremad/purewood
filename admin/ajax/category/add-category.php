<?php
header('Content-Type: application/json');
include('../../../config/config.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Slug converter function
function generateSlug($string) {
    // Convert to lowercase
    $slug = strtolower($string);
    // Replace non-alphanumeric characters with hyphens
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    // Trim hyphens from start and end
    $slug = trim($slug, '-');
    return $slug;
}

try {
    $name   = $_POST['name'] ?? '';
    $rawSlug   = $_POST['slug'] ?? '';
    $status = isset($_POST['status']) ? 1 : 0;
    $imageName = '';

    // Slug sanitization
    $slug = generateSlug(!empty($rawSlug) ? $rawSlug : $name);

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        $targetPath = '../../uploads/category/' . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
    }

    // Check if ID is present for update
    if (!empty($_POST['id'])) {
        $id = $_POST['id'];

        $sql = "UPDATE categories SET name = ?, slug = ?, status = ?";
        $params = [$name, $slug, $status];

        if ($imageName) {
            $sql .= ", image = ?";
            $params[] = $imageName;
        }

        $sql .= " WHERE id = ?";
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
    } else {
        // Insert new category
        $stmt = $conn->prepare("INSERT INTO categories (name, slug, image, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $slug, $imageName, $status]);

        echo json_encode(['success' => true, 'message' => 'Category added successfully']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}
