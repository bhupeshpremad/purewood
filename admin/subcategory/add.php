<?php include('../includes/header.php'); ?>
<?php include '../../config/config.php'; ?>

<?php
// Fetch all active categories
$categories = [];
$stmt = $conn->query("SELECT id, name FROM categories WHERE status = 1");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $categories[] = $row;
}
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 px-0">
            <?php include('../includes/sidebar.php'); ?>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 mt-4">
            <div class="bg-white rounded p-4 shadow border">
                <h2 id="form-title" class="text-xl font-semibold mb-4 text-white bg-dark p-2 rounded">Add Subcategory</h2>

                <form id="subcategoryForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="subcategory_id" value="">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">Select Category</label>
                            <select class="form-control" name="category_id" id="category_id" required>
                                <option value="">-- Select Category --</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Subcategory Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="slug" class="form-label">Subcategory Slug</label>
                            <input type="text" class="form-control" id="slug" name="slug" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Subcategory Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div id="image-preview" class="mt-2"></div>
                        </div>
                    </div>

                    <div class="row">
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
    const subcategoryId = urlParams.get('id');

    if (subcategoryId) {
        document.getElementById('form-title').textContent = "Edit Subcategory";
        document.getElementById('subcategory_id').value = subcategoryId;

        fetch(`../ajax/subcategory/get-subcategory.php?id=${subcategoryId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const sub = data.subcategory;
                    document.getElementById('name').value = sub.name;
                    document.getElementById('slug').value = sub.slug;
                    document.getElementById('category_id').value = sub.category_id;

                    if (sub.image) {
                        document.getElementById('image-preview').innerHTML =
                            `<img src="../../uploads/subcategory/${sub.image}" class="img-thumbnail" width="100">`;
                    }

                    document.getElementById('status').checked = sub.status == 1;
                } else {
                    toastr.error("Failed to fetch subcategory data");
                }
            });
    }

    document.getElementById('subcategoryForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        if (!formData.has("status")) {
            formData.append("status", 0);
        }

        fetch('../ajax/subcategory/add-subcategory.php', {
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
                toastr.error(result.message || 'Failed to save subcategory');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error("An error occurred while saving subcategory");
        });
    });
</script>

<?php include('../includes/footer.php'); ?>
