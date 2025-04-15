<?php
include '../includes/header.php';
include '../../config/config.php';

// डेटाबेस से कैटेगरी, सब-कैटेगरी प्राप्त करें (फ़िल्टर के लिए)
$sql_categories = "SELECT id, name FROM categories";
$categories = $conn->query($sql_categories)->fetchAll(PDO::FETCH_ASSOC);

$sql_subcategories = "SELECT id, name FROM subcategories";
$subcategories = $conn->query($sql_subcategories)->fetchAll(PDO::FETCH_ASSOC);

$sql_subsubcategories = "SELECT id, name FROM subsubcategories";
$subsubcategories = $conn->query($sql_subsubcategories)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <div class="content">
        <div class="container-fluid px-0">
            <div class="row">
                <div class="col-lg-2">
                    <?php include '../includes/sidebar.php'; ?>
                </div>
                <div class="col-lg-10 pt-3 px-3">
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2 bg-dark text-white">
                                <div class="col-sm-12">
                                    <h1 class="py-2">Products</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex align-items-center justify-content-between mb-3">
                                    <h3 class="card-title mb-0">Product List</h3>
                                    <div class="card-tools">
                                        <a href="add.php" class="btn btn-success"><i class="fas fa-plus"></i> Add New Product</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="filter_category">Category</label>
                                                <select class="form-control" id="filter_category">
                                                    <option value="">All Categories</option>
                                                    <?php foreach ($categories as $cat): ?>
                                                        <option value="<?php echo $cat['id']; ?>"><?php echo $cat['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="filter_subcategory">Sub Category</label>
                                                <select class="form-control" id="filter_subcategory">
                                                    <option value="">All Sub Categories</option>
                                                    <?php foreach ($subcategories as $subcat): ?>
                                                        <option value="<?php echo $subcat['id']; ?>"><?php echo $subcat['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="filter_subsubcategory">Sub Sub Category</label>
                                                <select class="form-control" id="filter_subsubcategory">
                                                    <option value="">All Sub Sub Categories</option>
                                                    <?php foreach ($subsubcategories as $subsubcat): ?>
                                                        <option value="<?php echo $subsubcat['id']; ?>"><?php echo $subsubcat['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="search_term">Search</label>
                                                <input type="text" class="form-control" id="search_term" placeholder="Enter product name or code">
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th data-sort="name">Name</th>
                                                <th data-sort="code">Code</th>
                                                <th>Image</th>
                                                <th>Category</th>
                                                <th>Sub Category</th>
                                                <th>Sub Sub Category</th>
                                                <th data-sort="price_per_20pcs">Price (per 20pcs)</th>
                                                <th data-sort="status">Status</th>
                                                <th data-sort="created_at">Created At</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="products_table_body">
                                            </tbody>
                                    </table>
                                    <div class="row mt-3">
                                        <div class="col-sm-12 col-md-5">
                                            <div class="dataTables_info" id="products_info" role="status" aria-live="polite">
                                                </div>
                                        </div>
                                        <div class="col-sm-12 col-md-7">
                                            <div class="dataTables_paginate paging_simple_numbers" id="products_paginate">
                                                <ul class="pagination">
                                                    </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    $(document).ready(function () {
        let currentPage = 1;
        let currentSortBy = 'id';
        let currentSortOrder = 'DESC';
        let currentCategoryId = '';
        let currentSubcategoryId = '';
        let currentSubSubcategoryId = '';
        let currentSearchTerm = '';
        const recordsPerPage = 10;

        function loadProducts(page, sortBy, sortOrder, categoryId, subcategoryId, subSubcategoryId, searchTerm) {
            $.ajax({
                url: '../ajax/products/fetch_products.php',
                type: 'GET',
                dataType: 'json',
                data: {
                    page: page,
                    limit: recordsPerPage,
                    sort_by: sortBy,
                    sort_order: sortOrder,
                    category_id: categoryId,
                    subcategory_id: subcategoryId,
                    subsubcategory_id: subSubcategoryId,
                    search: searchTerm
                },
                success: function (response) {
                    if (response.status) {
                        $('#products_table_body').empty();
                        if (response.products.length > 0) {
                            $.each(response.products, function (i, product) {
                                let statusClass = product.status == 1 ? 'btn-success' : 'btn-danger';
                                let statusText = product.status == 1 ? 'Active' : 'Inactive';
                                let imagePath = product.image ? `<?php echo AppConfig::baseUrl(); ?>/uploads/products/${product.code}/${product.image}` : '';
                                let createdAtDate = new Date(product.created_at);
                                let formattedDate = isNaN(createdAtDate.getTime()) ? 'Invalid Date' : createdAtDate.toLocaleDateString();

                                let statusButton = `
                                    <button class="btn btn-sm toggle-status ${statusClass}" data-id="${product.id}" data-status="${product.status}">
                                        ${statusText}
                                    </button>
                                `;

                                let row = `<tr>
                                    <td>${product.id}</td>
                                    <td>${product.name}</td>
                                    <td>${product.code}</td>
                                    <td>${imagePath ? `<img src="${imagePath}" alt="${product.name}" style="max-width: 50px; max-height: 50px;">` : ''}</td>
                                    <td>${product.category_name || ''}</td>
                                    <td>${product.subcategory_name || ''}</td>
                                    <td>${product.subsubcategory_name || ''}</td>
                                    <td>${product.price_per_20pcs ? parseFloat(product.price_per_20pcs).toFixed(2) : ''}</td>
                                    <td class="status-cell">${statusButton}</td>
                                    <td>${formattedDate}</td>
                                    <td>
                                        <a href="add.php?id=${product.id}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                        <button class="btn btn-sm btn-danger delete-product" data-id="${product.id}"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>`;
                                $('#products_table_body').append(row);
                            });

                            let paginationHtml = '';
                            if (response.total_pages > 1) {
                                paginationHtml += `<li class="paginate_button page-item previous ${response.current_page == 1 ? 'disabled' : ''}"><a href="#" class="page-link" data-page="${response.current_page - 1}">Previous</a></li>`;
                                for (let i = 1; i <= response.total_pages; i++) {
                                    paginationHtml += `<li class="paginate_button page-item ${response.current_page == i ? 'active' : ''}"><a href="#" class="page-link" data-page="${i}">${i}</a></li>`;
                                }
                                paginationHtml += `<li class="paginate_button page-item next ${response.current_page == response.total_pages ? 'disabled' : ''}"><a href="#" class="page-link" data-page="${response.current_page + 1}">Next</a></li>`;
                            }
                            $('#products_paginate ul').html(paginationHtml);

                            let start = (response.current_page - 1) * response.limit + 1;
                            let end = Math.min(response.total_records, response.current_page * response.limit);
                            $('#products_info').html(`Showing ${start} to ${end} of ${response.total_records} entries`);

                        } else {
                            $('#products_table_body').html('<tr><td colspan="11" class="text-center">No products found</td></tr>');
                            $('#products_paginate ul').empty();
                            $('#products_info').empty();
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", error);
                    toastr.error("Error loading products.");
                }
            });
        }

        loadProducts(currentPage, currentSortBy, currentSortOrder, currentCategoryId, currentSubcategoryId, currentSubSubcategoryId, currentSearchTerm);

        $('thead th[data-sort]').on('click', function () {
            const sortBy = $(this).data('sort');
            let newSortBy = sortBy;
            if (sortBy === 'name') newSortBy = 'p.name';
            else if (sortBy === 'code') newSortBy = 'p.code';
            else if (sortBy === 'price_per_20pcs') newSortBy = 'p.price_per_20pcs';
            else if (sortBy === 'status') newSortBy = 'p.status';
            else if (sortBy === 'created_at') newSortBy = 'p.created_at';

            if (newSortBy === currentSortBy) {
                currentSortOrder = currentSortOrder === 'ASC' ? 'DESC' : 'ASC';
            } else {
                currentSortBy = newSortBy;
                currentSortOrder = 'ASC';
            }
            loadProducts(1, currentSortBy, currentSortOrder, currentCategoryId, currentSubcategoryId, currentSubSubcategoryId, currentSearchTerm);
        });

        $('#filter_category').on('change', function () {
            currentCategoryId = $(this).val();
            loadProducts(1, currentSortBy, currentSortOrder, currentCategoryId, currentSubcategoryId, currentSubSubcategoryId, currentSearchTerm);
            var categoryId = $(this).val();
            $.ajax({
                url: '../ajax/categories/fetch_subcategories.php',
                type: 'GET',
                dataType: 'json',
                data: { category_id: categoryId },
                success: function (response) {
                    $('#filter_subcategory').empty().append('<option value="">All Sub Categories</option>');
                    $.each(response, function (key, value) {
                        $('#filter_subcategory').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    $('#filter_subsubcategory').empty().append('<option value="">All Sub Sub Categories</option>');
                    currentSubcategoryId = '';
                    currentSubSubcategoryId = '';
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching subcategories:", error);
                }
            });
        });

        $('#filter_subcategory').on('change', function () {
            currentSubcategoryId = $(this).val();
            loadProducts(1, currentSortBy, currentSortOrder, currentCategoryId, currentSubcategoryId, currentSubSubcategoryId, currentSearchTerm);
            var subcategoryId = $(this).val();
            $.ajax({
                url: '../ajax/categories/fetch_subsubcategories.php',
                type: 'GET',
                dataType: 'json',
                data: { subcategory_id: subcategoryId },
                success: function (response) {
                    $('#filter_subsubcategory').empty().append('<option value="">All Sub Sub Categories</option>');
                    $.each(response, function (key, value) {
                        $('#filter_subsubcategory').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                    currentSubSubcategoryId = '';
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching sub-subcategories:", error);
                }
            });
        });

        $('#filter_subsubcategory').on('change', function () {
            currentSubSubcategoryId = $(this).val();
            loadProducts(1, currentSortBy, currentSortOrder, currentCategoryId, currentSubcategoryId, currentSubSubcategoryId, currentSearchTerm);
        });

        $('#search_term').on('keyup', function () {
            currentSearchTerm = $(this).val().trim();
            loadProducts(1, currentSortBy, currentSortOrder, currentCategoryId, currentSubcategoryId, currentSubSubcategoryId, currentSearchTerm);
        });

        $(document).on('click', '#products_paginate .page-link', function (e) {
            e.preventDefault();
            const page = $(this).data('page');
            if (page) {
                currentPage = page;
                loadProducts(currentPage, currentSortBy, currentSortOrder, currentCategoryId, currentSubcategoryId, currentSubSubcategoryId, currentSearchTerm);
            }
        });

        $(document).on('click', '.delete-product', function () {
            const productId = $(this).data('id');
            if (confirm('Are you sure you want to delete this product?')) {
                $.ajax({
                    url: '../ajax/products/delete_product.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { id: productId },
                    success: function (response) {
                        if (response.status) {
                            toastr.success(response.message);
                            loadProducts(currentPage, currentSortBy, currentSortOrder, currentCategoryId, currentSubcategoryId, currentSubSubcategoryId, currentSearchTerm);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("AJAX error:", error);
                        toastr.error("Error deleting product.");
                    }
                });
            }
        });

        $(document).on('click', '.toggle-status', function () {
            const productId = $(this).data('id');
            const currentStatus = $(this).data('status');
            const newStatus = currentStatus == 1 ? 0 : 1;
            const button = $(this);

            $.ajax({
                url: '../ajax/products/update_status.php',
                type: 'POST',
                dataType: 'json',
                data: { id: productId, status: newStatus },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        button.data('status', newStatus);
                        if (newStatus == 1) {
                            button.removeClass('btn-danger').addClass('btn-success').text('Active');
                        } else {
                            button.removeClass('btn-success').addClass('btn-danger').text('Inactive');
                        }
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX error:", error);
                    toastr.error("Error updating product status.");
                }
            });
        });
    });
</script>