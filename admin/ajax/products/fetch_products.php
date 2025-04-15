<?php
include '../../../config/config.php';

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
$limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? intval($_GET['limit']) : 10;
$offset = ($page - 1) * $limit;

$category_id = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : 0;
$subcategory_id = isset($_GET['subcategory_id']) && is_numeric($_GET['subcategory_id']) ? intval($_GET['subcategory_id']) : 0;
$subsubcategory_id = isset($_GET['subsubcategory_id']) && is_numeric($_GET['subsubcategory_id']) ? intval($_GET['subsubcategory_id']) : 0;

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

$sort_by = isset($_GET['sort_by']) ? trim($_GET['sort_by']) : 'p.id';
$sort_order = isset($_GET['sort_order']) ? trim($_GET['sort_order']) : 'DESC';

$where_conditions = [];
$where_params = [];

if ($category_id > 0) {
    $where_conditions[] = 'p.category_id = :category_id';
    $where_params[':category_id'] = $category_id;
}
if ($subcategory_id > 0) {
    $where_conditions[] = 'p.subcategory_id = :subcategory_id';
    $where_params[':subcategory_id'] = $subcategory_id;
}
if ($subsubcategory_id > 0) {
    $where_conditions[] = 'p.subsubcategory_id = :subsubcategory_id';
    $where_params[':subsubcategory_id'] = $subsubcategory_id;
}
if (!empty($search_term)) {
    $where_conditions[] = '(p.name LIKE :search_term OR p.code LIKE :search_term)';
    $where_params[':search_term'] = '%' . $search_term . '%';
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

$allowed_sort_columns = ['p.id', 'p.name', 'p.code', 'p.price_per_20pcs', 'p.created_at', 'c.name', 'sc.name', 'ssc.name', 'p.status'];
if (!in_array($sort_by, $allowed_sort_columns)) {
    $sort_by = 'p.id';
}
$sort_order = strtoupper($sort_order) == 'ASC' ? 'ASC' : 'DESC';

try {
    $sql_count = "SELECT COUNT(p.id) as total
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN subcategories sc ON p.subcategory_id = sc.id
                  LEFT JOIN subsubcategories ssc ON p.subsubcategory_id = ssc.id
                  " . $where_clause;
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->execute($where_params);
    $total_records = $stmt_count->fetchColumn();
    $total_pages = ceil($total_records / $limit);

    $sql_fetch = "SELECT p.id, p.name, p.code, p.image,
                           c.name as category_name,
                           sc.name as subcategory_name,
                           ssc.name as subsubcategory_name,
                           p.price_per_20pcs, p.status, p.created_at
                  FROM products p
                  LEFT JOIN categories c ON p.category_id = c.id
                  LEFT JOIN subcategories sc ON p.subcategory_id = sc.id
                  LEFT JOIN subsubcategories ssc ON p.subsubcategory_id = ssc.id
                  " . $where_clause . "
                  ORDER BY " . $sort_by . " " . $sort_order . "
                  LIMIT :limit OFFSET :offset";
    $stmt_fetch = $conn->prepare($sql_fetch);
    $stmt_fetch->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt_fetch->bindParam(':offset', $offset, PDO::PARAM_INT);
    foreach ($where_params as $key => $value) {
        $stmt_fetch->bindValue($key, $value);
    }
    $stmt_fetch->execute();
    $products = $stmt_fetch->fetchAll(PDO::FETCH_ASSOC);

    $response = [
        'status' => true,
        'products' => $products,
        'total_records' => $total_records,
        'total_pages' => $total_pages,
        'current_page' => $page,
        'limit' => $limit,
        'sort_by' => $sort_by,
        'sort_order' => $sort_order,
        'filters' => [
            'category_id' => $category_id,
            'subcategory_id' => $subcategory_id,
            'subsubcategory_id' => $subsubcategory_id,
            'search' => $search_term
        ]
    ];

} catch (PDOException $e) {
    $response = ['status' => false, 'message' => 'Database error: ' . $e->getMessage()];
}

header('Content-Type: application/json');
echo json_encode($response);
?>