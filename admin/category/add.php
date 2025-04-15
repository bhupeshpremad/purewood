<?php include('../includes/header.php'); ?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 px-0">
            <?php include('../includes/sidebar.php'); ?>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 mt-4">
            <div class="bg-white rounded p-4 shadow border">
                <h2 id="form-title" class="text-xl font-semibold mb-4 text-white bg-dark p-2 rounded">Add Category</h2>

                <form id="categoryForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="category_id" value="">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Category Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div id="image-preview" class="mt-2"></div>
                        </div>

                        <div class="col-md-6 mb-3 d-flex align-items-center pt-4">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input bg-success" id="status" name="status" checked>
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get('id');

    if (categoryId) {
        document.getElementById('form-title').textContent = "Edit Category";
        document.getElementById('category_id').value = categoryId;

        fetch(`../ajax/category/get-category.php?id=${categoryId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('name').value = data.category.name;
                    document.getElementById('slug').value = data.category.slug;

                    if (data.category.image) {
                        document.getElementById('image-preview').innerHTML =
                            `<img src="../uploads/category/${data.category.image}" class="img-thumbnail" width="100">`;
                    }

                    document.getElementById('status').checked = data.category.status == 1;
                } else {
                    toastr.error("Failed to fetch category data");
                }
            });
    }

    document.getElementById('categoryForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        if (!formData.has("status")) {
            formData.append("status", 0);
        }

        fetch('../ajax/category/add-category.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    toastr.success(result.message);
                    setTimeout(() => {
                        window.location.href = 'add.php';
                    }, 1500);
                } else {
                    toastr.error(result.message || 'Failed to save category');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error("An error occurred while saving category");
            });
    });
</script>

<?php include('../includes/footer.php'); ?>
