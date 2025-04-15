<?php
include '../includes/header.php';
include '../../config/config.php';


$categories = $conn->query("SELECT id, name FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 px-0">
            <?php include '../includes/sidebar.php'; ?>
        </div>
        <div class="col-lg-10 pt-5 px-4">
            <div class="bg-black text-white p-2 mb-4">
                <h4 class="m-0">Add / Edit Sub-Subcategory</h4>
            </div>

            <form id="subsubcatForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="id">
                <div class="row">

                    <div class="form-group col-lg-6">
                        <label class="mb-3">Category</label>
                        <select name="category_id" id="category_id" class="form-control mb-3" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="mb-3">Subcategory</label>
                        <select name="subcategory_id" id="subcategory_id" class="form-control mb-3" required>
                            <option value="">Select Subcategory</option>
                        </select>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="mb-3">Sub-Subcategory Name</label>
                        <input type="text" name="name" id="name" class="form-control mb-3" required>
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="mb-3">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control mb-3">
                    </div>

                    <div class="form-group col-lg-6">
                        <label class="mb-3">Image</label>
                        <input type="file" name="image" class="form-control mb-3">
                        <div id="existingImage" class="mt-2"></div>
                    </div>

                    <div class="form-group col-lg-6 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input bg-success" id="status" name="status">
                            <label class="form-check-label" for="status">Active</label>
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary mt-4">Save</button>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    function loadSubcategories(catId, selectedSubcatId = null) {
        $('#subcategory_id').html('<option value="">Loading...</option>');
        if (catId) {
            $.post('../ajax/subsubcategory/fetch-subcategories-by-category.php', { category_id: catId }, function (res) {
                let options = '<option value="">Select Subcategory</option>';
                if (res.status) {
                    $.each(res.data, function (i, subcat) {
                        options += `<option value="${subcat.id}">${subcat.name}</option>`;
                    });
                }
                $('#subcategory_id').html(options);
                if (selectedSubcatId) {
                    $('#subcategory_id').val(selectedSubcatId);
                }
            }, 'json');
        } else {
            $('#subcategory_id').html('<option value="">Select Subcategory</option>');
        }
    }

    $('#category_id').on('change', function () {
        let catId = $(this).val();
        loadSubcategories(catId);
    });

    $('#subsubcatForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        if (!formData.get('status')) {
            formData.set('status', 0);
        }

        $.ajax({
            url: '../ajax/subsubcategory/add-subsubcategory.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (r) {
                console.log(r); // Debugging: Check the full response object
                if (r.status) {
                    toastr.success(r.message);
                    if (r.image_path) {
                        $('#existingImage').html(`<img src="../../${r.image_path}" height="60" class="img-thumbnail">`);
                        $('input[name="image"]').val(''); // Clear the file input after successful upload
                    }
                    setTimeout(() => {
                        window.location.href = 'add.php';
                    }, 1500);
                } else {
                    toastr.error(r.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error, xhr.responseText); // Debugging: Log AJAX errors
                toastr.error("Something went wrong!");
            }
        });
    });

    // Edit mode logic
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('id');
    if (editId) {
        $.getJSON('../ajax/subsubcategory/get-subsubcategory.php', { id: editId }, function (res) {
            console.log(res); // Debugging: Check the response object in edit mode
            if (res.status) {
                let d = res.data;
                $('#id').val(d.id);
                $('#name').val(d.name);
                $('#slug').val(d.slug);
                $('#status').prop('checked', d.status == 1);

                $('#category_id').val(d.category_id);
                loadSubcategories(d.category_id, d.subcategory_id);

                if (d.image) {
                    $('#existingImage').html(`<img src="../../${d.image}" height="60" class="img-thumbnail">`);
                }
            } else {
                toastr.error("Sub-subcategory not found");
            }
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>