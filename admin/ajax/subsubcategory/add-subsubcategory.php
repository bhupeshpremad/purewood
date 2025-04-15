<?php
include '../../../config/config.php';

header('Content-Type: application/json');
ini_set('display_errors', 0); // उत्पादन में एरर दिखाना बंद करें
error_reporting(0); // उत्पादन में सभी एरर रिपोर्टिंग बंद करें

$response = ['status' => false, 'message' => 'Something went wrong!'];

// Slug converter function
function generateSlug($string) {
    $slug = strtolower($string);
    $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
    $slug = trim($slug, '-');
    return $slug;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $name = trim($_POST['name']);
    $rawSlug = trim($_POST['slug']);
    $status = isset($_POST['status']) ? intval($_POST['status']) : 1;

    // Slug sanitization
    $slug = generateSlug(!empty($rawSlug) ? $rawSlug : $name);

    // Image Upload Handling
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "../../uploads/subsubcategories/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $filename = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = "uploads/subsubcategories/" . $filename;
        }
    }

    try {
        if ($id > 0) {
            // Update
            $query = "UPDATE subsubcategories SET category_id=?, subcategory_id=?, name=?, slug=?, status=?";
            $params = [$category_id, $subcategory_id, $name, $slug, $status];

            if ($image) {
                $query .= ", image=?";
                $params[] = $image;
            }

            $query .= ", updated_at=NOW() WHERE id=?";
            $params[] = $id;

            $stmt = $conn->prepare($query);
            $result = $stmt->execute($params);

            $response = [
                'status' => $result,
                'message' => $result ? 'Sub-Subcategory updated successfully!' : 'Failed to update'
            ];
        } else {
            // Insert
            $stmt = $conn->prepare("INSERT INTO subsubcategories (category_id, subcategory_id, name, slug, image, status) VALUES (?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$category_id, $subcategory_id, $name, $slug, $image, $status]);

            $response = [
                'status' => $result,
                'message' => $result ? 'Sub-Subcategory added successfully!' : 'Failed to add'
            ];
        }
    } catch (PDOException $e) {
        $response = ['status' => false, 'message' => 'Database error: ' . $e->getMessage()];
    }
}

echo json_encode($response);

?>