<?php
include '../../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : null;
    $subcategory_id = isset($_POST['subcategory_id']) ? intval($_POST['subcategory_id']) : null;
    $subsubcategory_id = isset($_POST['subsubcategory_id']) ? intval($_POST['subsubcategory_id']) : null;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $brandName = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';
    $code = isset($_POST['code']) ? trim($_POST['code']) : '';
    $brand_name = isset($_POST['brand_name']) ? trim($_POST['brand_name']) : '';
    $short_description = isset($_POST['short_description']) ? trim($_POST['short_description']) : '';
    $dimensions = isset($_POST['dimensions']) ? trim($_POST['dimensions']) : null;
    $packing_box_size = isset($_POST['packing_box_size']) ? trim($_POST['packing_box_size']) : null;
    $packing_type = isset($_POST['packing_type']) ? trim($_POST['packing_type']) : 'Flat pack';
    $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : null;
    $cbm = isset($_POST['cbm']) ? floatval($_POST['cbm']) : null;
    $loadability = isset($_POST['loadability']) ? trim($_POST['loadability']) : null;
    $assembly_required = isset($_POST['assembly_required']) ? trim($_POST['assembly_required']) : 'No';
    $assembly_tools = isset($_POST['assembly_tools']) ? trim($_POST['assembly_tools']) : null;
    $material = isset($_POST['material']) ? implode(',', $_POST['material']) : null;
    $price_per_20pcs = isset($_POST['price_per_20pcs']) ? floatval($_POST['price_per_20pcs']) : null;
    $primary_material = isset($_POST['primary_material']) ? trim($_POST['primary_material']) : null;
    $secondary_material = isset($_POST['secondary_material']) ? trim($_POST['secondary_material']) : null;
    $finish_appearance = isset($_POST['finish_appearance']) ? trim($_POST['finish_appearance']) : null;
    $color_variant = isset($_POST['color_variant']) ? trim($_POST['color_variant']) : null;
    $customization_options_array = isset($_POST['customization_options']) ? $_POST['customization_options'] : [];
    $customization_options = !empty($customization_options_array) ? implode(',', $customization_options_array) : null;
    $status = isset($_POST['status']) ? 1 : 0;

    $image = null;
    $other_images_json = null;
    $uploadBaseDir = '../../../uploads/products/';
    $productCodeDir = $uploadBaseDir . $code . '/';

    // Create product code directory if it doesn't exist
    if (!is_dir($productCodeDir)) {
        if (!mkdir($productCodeDir, 0755, true)) {
            $response = ['success' => false, 'message' => 'Failed to create product directory.'];
            echo json_encode($response);
            exit();
        }
    }

    // Function to handle file uploads with specific naming
    function uploadFile($file, $uploadPath, $baseName) {
        if (isset($file) && $file['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = basename($file['name']);
            $fileExt = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (in_array($fileExt, $allowed)) {
                $newFilename = $baseName . '.' . $fileExt;
                $destination = $uploadPath . $newFilename;
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    return $newFilename;
                } else {
                    return false;
                }
            } else {
                return 'invalid_type';
            }
        }
        return null;
    }

    // Handle main image upload
    $mainImageBaseName = $code . '_main';
    $uploadedImage = uploadFile($_FILES['image'], $productCodeDir, $mainImageBaseName);
    if ($uploadedImage === 'invalid_type') {
        $response = ['success' => false, 'message' => 'Invalid file type for main image. Only JPG, JPEG, PNG, and GIF are allowed.'];
        echo json_encode($response);
        exit();
    } elseif ($uploadedImage) {
        $image = $uploadedImage;
    }

    // Handle other images upload
    $otherImagesArray = [];
    if (isset($_FILES['other_images']) && is_array($_FILES['other_images']['name'])) {
        $numOtherImages = count($_FILES['other_images']['name']);
        for ($i = 0; $i < min($numOtherImages, 3); $i++) {
            $otherFile = [
                'name' => $_FILES['other_images']['name'][$i],
                'type' => $_FILES['other_images']['type'][$i],
                'tmp_name' => $_FILES['other_images']['tmp_name'][$i],
                'error' => $_FILES['other_images']['error'][$i],
                'size' => $_FILES['other_images']['size'][$i],
            ];
            $otherImageBaseName = $code . '_other-' . sprintf('%02d', ($i + 1));
            $uploadedOtherImage = uploadFile($otherFile, $productCodeDir, $otherImageBaseName);
            if ($uploadedOtherImage === 'invalid_type') {
                $response = ['success' => false, 'message' => 'Invalid file type for other images. Only JPG, JPEG, PNG, and GIF are allowed.'];
                echo json_encode($response);
                exit();
            } elseif ($uploadedOtherImage) {
                $otherImagesArray[] = $uploadedOtherImage;
            }
        }
        $other_images_json = !empty($otherImagesArray) ? json_encode($otherImagesArray) : null;
    }

    // Prepare data for database
    $productData = [
        'category_id' => $category_id,
        'subcategory_id' => $subcategory_id,
        'subsubcategory_id' => $subsubcategory_id,
        'name' => $name,
        'brandName' => $brandName,
        'code' => $code,
        'slug' => slugify($name),
        'dimensions' => $dimensions,
        'packing_box_size' => $packing_box_size,
        'packing_type' => $packing_type,
        'weight' => $weight,
        'cbm' => $cbm,
        'loadability' => $loadability,
        'assembly_required' => $assembly_required,
        'assembly_tools' => $assembly_tools,
        'material' => $material,
        'price_per_20pcs' => $price_per_20pcs,
        'primary_material' => $primary_material,
        'secondary_material' => $secondary_material,
        'finish_appearance' => $finish_appearance,
        'color_variant' => $color_variant,
        'customization_options' => $customization_options,
        'status' => $status,
        'brand_name' => $brand_name,
        'short_description' => $short_description,
        'image' => $image,
        'other_images' => $other_images_json,
    ];

    // Handle image updates for existing product
    if ($id > 0) {
        // Fetch existing product data to check for old images and code
        $sql_select = "SELECT code, image, other_images FROM products WHERE id = :id";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_select->execute();
        $existingProduct = $stmt_select->fetch(PDO::FETCH_ASSOC);
        $oldCode = $existingProduct['code'];
        $oldProductCodeDir = $uploadBaseDir . $oldCode . '/';

        // If the product code has changed, create a new directory and move images
        if ($code !== $oldCode) {
            if (!is_dir($productCodeDir)) {
                if (!mkdir($productCodeDir, 0755, true)) {
                    $response = ['success' => false, 'message' => 'Failed to create new product directory.'];
                    echo json_encode($response);
                    exit();
                }
            }
            // Move existing images to the new directory
            if (is_dir($oldProductCodeDir)) {
                if ($existingProduct['image'] && file_exists($oldProductCodeDir . $existingProduct['image'])) {
                    rename($oldProductCodeDir . $existingProduct['image'], $productCodeDir . $code . '_main.' . pathinfo($existingProduct['image'], PATHINFO_EXTENSION));
                    $productData['image'] = $code . '_main.' . pathinfo($existingProduct['image'], PATHINFO_EXTENSION);
                }
                if ($existingProduct['other_images']) {
                    $oldOtherImages = json_decode($existingProduct['other_images'], true);
                    if (is_array($oldOtherImages)) {
                        $newOtherImages = [];
                        foreach ($oldOtherImages as $index => $oldImage) {
                            if (file_exists($oldProductCodeDir . $oldImage)) {
                                $newOtherImageName = $code . '_other-' . sprintf('%02d', ($index + 1)) . '.' . pathinfo($oldImage, PATHINFO_EXTENSION);
                                rename($oldProductCodeDir . $oldImage, $productCodeDir . $newOtherImageName);
                                $newOtherImages[] = $newOtherImageName;
                            }
                        }
                        $productData['other_images'] = json_encode($newOtherImages);
                    }
                }
                // Optionally delete the old directory if it's empty
                $files = glob($oldProductCodeDir . '*');
                if (empty($files)) {
                    rmdir($oldProductCodeDir);
                }
            }
        } else {
            // If product code hasn't changed, handle image updates in the same directory
            if ($image) {
                // Delete old main image if a new one is uploaded
                if ($existingProduct && $existingProduct['image'] && file_exists($productCodeDir . $existingProduct['image']) && $image !== $existingProduct['image']) {
                    unlink($productCodeDir . $existingProduct['image']);
                }
            } else {
                // Keep the old image if no new one is uploaded
                $productData['image'] = $existingProduct['image'];
            }

            if ($other_images_json !== null) {
                // Delete old other images if new ones are uploaded
                if ($existingProduct && $existingProduct['other_images']) {
                    $oldOtherImages = json_decode($existingProduct['other_images'], true);
                    $newOtherImages = json_decode($other_images_json, true);
                    if (is_array($oldOtherImages)) {
                        foreach ($oldOtherImages as $oldImage) {
                            $found = false;
                            if (is_array($newOtherImages)) {
                                foreach ($newOtherImages as $newImage) {
                                    if ($oldImage === $newImage) {
                                        $found = true;
                                        break;
                                    }
                                }
                            }
                            if (!$found && file_exists($productCodeDir . $oldImage)) {
                                unlink($productCodeDir . $oldImage);
                            }
                        }
                    }
                }
            } else {
                // Keep the old other images if no new ones are uploaded
                $productData['other_images'] = $existingProduct['other_images'];
            }
        }

        // Update existing product data in the database
        $sql_update = "UPDATE products SET
                                    category_id = :category_id,
                                    subcategory_id = :subcategory_id,
                                    subsubcategory_id = :subsubcategory_id,
                                    name = :name,
                                    code = :code,
                                    slug = :slug,
                                    dimensions = :dimensions,
                                    packing_box_size = :packing_box_size,
                                    packing_type = :packing_type,
                                    weight = :weight,
                                    cbm = :cbm,
                                    loadability = :loadability,
                                    assembly_required = :assembly_required,
                                    assembly_tools = :assembly_tools,
                                    material = :material,
                                    price_per_20pcs = :price_per_20pcs,
                                    primary_material = :primary_material,
                                    secondary_material = :secondary_material,
                                    finish_appearance = :finish_appearance,
                                    color_variant = :color_variant,
                                    customization_options = :customization_options,
                                    status = :status,
                                    brand_name = :brand_name,
                                    short_description = :short_description,
                                    image = :image,
                                    other_images = :other_images,
                                    updated_at = CURRENT_TIMESTAMP()
                                    WHERE id = :id";

        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_update->bindParam(':category_id', $productData['category_id'], PDO::PARAM_INT);
        $stmt_update->bindParam(':subcategory_id', $productData['subcategory_id'], PDO::PARAM_INT);
        $stmt_update->bindParam(':subsubcategory_id', $productData['subsubcategory_id'], PDO::PARAM_INT);
        $stmt_update->bindParam(':name', $productData['name'], PDO::PARAM_STR);
        $stmt_update->bindParam(':code', $productData['code'], PDO::PARAM_STR);
        $stmt_update->bindParam(':slug', $productData['slug'], PDO::PARAM_STR);
        $stmt_update->bindParam(':dimensions', $product['dimensions'], PDO::PARAM_STR);
        $stmt_update->bindParam(':dimensions', $productData['dimensions'], PDO::PARAM_STR);
        $stmt_update->bindParam(':packing_box_size', $productData['packing_box_size'], PDO::PARAM_STR);
        $stmt_update->bindParam(':packing_type', $productData['packing_type'], PDO::PARAM_STR);
        $stmt_update->bindParam(':weight', $productData['weight'], PDO::PARAM_STR);
        $stmt_update->bindParam(':cbm', $productData['cbm'], PDO::PARAM_STR);
        $stmt_update->bindParam(':loadability', $productData['loadability'], PDO::PARAM_STR);
        $stmt_update->bindParam(':assembly_required', $productData['assembly_required'], PDO::PARAM_STR);
        $stmt_update->bindParam(':assembly_tools', $productData['assembly_tools'], PDO::PARAM_STR);
        $stmt_update->bindParam(':material', $productData['material'], PDO::PARAM_STR);
        $stmt_update->bindParam(':price_per_20pcs', $productData['price_per_20pcs'], PDO::PARAM_STR);
        $stmt_update->bindParam(':primary_material', $productData['primary_material'], PDO::PARAM_STR);
        $stmt_update->bindParam(':secondary_material', $productData['secondary_material'], PDO::PARAM_STR);
        $stmt_update->bindParam(':finish_appearance', $productData['finish_appearance'], PDO::PARAM_STR);
        $stmt_update->bindParam(':color_variant', $productData['color_variant'], PDO::PARAM_STR);
        $stmt_update->bindParam(':customization_options', $productData['customization_options'], PDO::PARAM_STR);
        $stmt_update->bindParam(':status', $productData['status'], PDO::PARAM_INT);
        $stmt_update->bindParam(':brand_name', $productData['brand_name'], PDO::PARAM_STR);
        $stmt_update->bindParam(':short_description', $productData['short_description'], PDO::PARAM_STR);
        $stmt_update->bindParam(':image', $productData['image'], PDO::PARAM_STR);
        $stmt_update->bindParam(':other_images', $productData['other_images'], PDO::PARAM_STR);

        if ($stmt_update->execute()) {
            $response = ['success' => true, 'message' => 'Product updated successfully!'];
        } else {
            $errorInfo = $stmt_update->errorInfo();
            $response = ['success' => false, 'message' => 'Error updating product: ' . $errorInfo[2]];
            // Log $errorInfo for detailed debugging
            error_log("Error updating product (ID: $id): " . print_r($errorInfo, true));
        }

    } else {
        // Insert new product
        $sql_insert = "INSERT INTO products (category_id, subcategory_id, subsubcategory_id, name, code, slug, image, other_images, dimensions, packing_box_size, packing_type, weight, cbm, loadability, assembly_required, assembly_tools, material, price_per_20pcs, primary_material, secondary_material, finish_appearance, color_variant, customization_options, status, brand_name, short_description)
                            VALUES (:category_id, :subcategory_id, :subsubcategory_id, :name, :code, :slug, :image, :other_images, :dimensions, :packing_box_size, :packing_type, :weight, :cbm, :loadability, :assembly_required, :assembly_tools, :material, :price_per_20pcs, :primary_material, :secondary_material, :finish_appearance, :color_variant, :customization_options, :status, :brand_name, :short_description)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindParam(':category_id', $productData['category_id'], PDO::PARAM_INT);
        $stmt_insert->bindParam(':subcategory_id', $productData['subcategory_id'], PDO::PARAM_INT);
        $stmt_insert->bindParam(':subsubcategory_id', $productData['subsubcategory_id'], PDO::PARAM_INT);
        $stmt_insert->bindParam(':name', $productData['name'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':code', $productData['code'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':slug', $productData['slug'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':image', $productData['image'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':other_images', $productData['other_images'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':dimensions', $productData['dimensions'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':packing_box_size', $productData['packing_box_size'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':packing_type', $productData['packing_type'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':weight', $productData['weight'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':cbm', $productData['cbm'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':loadability', $productData['loadability'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':assembly_required', $productData['assembly_required'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':assembly_tools', $productData['assembly_tools'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':material', $productData['material'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':price_per_20pcs', $productData['price_per_20pcs'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':primary_material', $productData['primary_material'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':secondary_material', $productData['secondary_material'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':finish_appearance', $productData['finish_appearance'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':color_variant', $productData['color_variant'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':customization_options', $productData['customization_options'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':status', $productData['status'], PDO::PARAM_INT);
        $stmt_insert->bindParam(':brand_name', $productData['brand_name'], PDO::PARAM_STR);
        $stmt_insert->bindParam(':short_description', $productData['short_description'], PDO::PARAM_STR);

        if ($stmt_insert->execute()) {
            $response = ['success' => true, 'message' => 'Product saved successfully!'];
        } else {
            $errorInfo = $stmt_insert->errorInfo();
            $response = ['success' => false, 'message' => 'Error saving product: ' . $errorInfo[2]];
            // Log $errorInfo for detailed debugging
            error_log("Error saving new product: " . print_r($errorInfo, true));
        }
    }

    echo json_encode($response);

} else {
    // If not a POST request
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

// Function to generate a URL-friendly slug
function slugify($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}
?>