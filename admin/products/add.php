<?php
include '../includes/header.php';
include '../../config/config.php';

$product_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;

$sql_categories = "SELECT id, name FROM categories WHERE status = 1";
$categories = $conn->query($sql_categories)->fetchAll(PDO::FETCH_ASSOC);

$material_options = ['Cane', 'Fabric', 'Leather/Leatherette', 'Marble', 'Metal', 'Wood'];
$primary_material_options = ['Mango', 'Acacia', 'Oak', 'Beech'];
?>

<div class="row">
    <?php include '../includes/sidebar.php'; ?>
    <div class="content-wrapper col-lg-10 pt-5">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0"><?php echo ($product_id > 0) ? 'Edit Product' : 'Add New Product'; ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container-fluid">
                <form id="productForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $product_id; ?>">

                    <div class="card mt-3">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title">Public View Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name" class="mb-2">Product Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="code" class="mb-2">Product Code</label>
                                        <input type="text" class="form-control" id="code" name="code" placeholder="Enter product code" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="brand_name" class="mb-2">Brand Name</label>
                                        <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Enter brand name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="category_id" class="mb-2">Category</label>
                                        <select class="form-control" id="category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="subcategory_id" class="mb-2">Sub Category</label>
                                        <select class="form-control" id="subcategory_id" name="subcategory_id">
                                            <option value="">Select Sub Category</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="subsubcategory_id" class="mb-2">Sub Sub Category</label>
                                        <select class="form-control" id="subsubcategory_id" name="subsubcategory_id">
                                            <option value="">Select Sub Sub Category</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="short_description" class="mb-2">Short Description</label>
                                        <textarea class="form-control" id="short_description" name="short_description" rows="3" placeholder="Enter a short description of the product"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="dimensions" class="mb-2">Product Dimensions (in cms)</label>
                                        <input type="text" class="form-control" id="dimensions" name="dimensions" placeholder="e.g., 10x20x30">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="packing_box_size" class="mb-2">Packing Box Size (in cms)</label>
                                        <input type="text" class="form-control" id="packing_box_size" name="packing_box_size" placeholder="e.g., 12x22x32">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="packing_type" class="mb-2">Packing Type</label>
                                        <select class="form-control" id="packing_type" name="packing_type">
                                            <option value="Flat pack">Flat pack</option>
                                            <option value="Assembled">Assembled</option>
                                            <option value="Knockdown">Knockdown</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="weight" class="mb-2">Product Weight (in KG)</label>
                                        <input type="number" class="form-control" id="weight" name="weight" step="0.01">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="cbm" class="mb-2">Product CBM (Cubic Meter)</label>
                                        <input type="number" class="form-control" id="cbm" name="cbm" step="0.001">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="loadability" class="mb-2">Product Loadability</label>
                                        <input type="text" class="form-control" id="loadability" name="loadability" placeholder="e.g., 20ft Container: 100 pcs">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="assembly_tools" class="mb-2">Assembly Tools/Manual</label>
                                        <input type="text" class="form-control" id="assembly_tools" name="assembly_tools" placeholder="e.g., Allen key, manual included">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="mb-2">Assembly Required</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="assembly_required" id="assembly_required_yes" value="Yes">
                                                <label class="form-check-label" for="assembly_required_yes">Yes</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="assembly_required" id="assembly_required_no" value="No" checked>
                                                <label class="form-check-label" for="assembly_required_no">No</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="material" class="mb-2">Material</label>
                                        <select class="form-control select2" id="material" name="material[]" multiple>
                                            <?php foreach ($material_options as $materialOption): ?>
                                                <option value="<?php echo htmlspecialchars($materialOption); ?>"><?php echo htmlspecialchars($materialOption); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="price_per_20pcs" class="mb-2">Price Per 20pcs</label>
                                        <input type="number" class="form-control" id="price_per_20pcs" name="price_per_20pcs" step="0.01">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title">Admin View Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="primary_material" class="mb-2">Primary Material</label>
                                        <select class="form-control" id="primary_material" name="primary_material">
                                            <option value="">Select Primary Material</option>
                                            <?php foreach ($primary_material_options as $primaryMaterial): ?>
                                                <option value="<?php echo htmlspecialchars($primaryMaterial); ?>"><?php echo htmlspecialchars($primaryMaterial); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="secondary_material" class="mb-2">Secondary Material (If applicable)</label>
                                        <input type="text" class="form-control" id="secondary_material" name="secondary_material" placeholder="e.g., Metal Frame, Upholstery">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="finish_appearance" class="mb-2">Finish & Appearance</label>
                                        <textarea class="form-control" id="finish_appearance" name="finish_appearance" rows="3" placeholder="Enter details about finish and appearance"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="color_variant" class="mb-2">Color Variant</label>
                                        <input type="text" class="form-control" id="color_variant" name="color_variant" placeholder="Enter color variants (e.g., Red, Blue, Green)">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="mb-2">Customization Option (Color, Material, Finish)</label>
                                        <div class="d-flex gap-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="customization_options[]" value="Color" id="customization_options_color">
                                                <label class="form-check-label" for="customization_options_color">Color</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="customization_options[]" value="Material" id="customization_options_material">
                                                <label class="form-check-label" for="customization_options_material">Material</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="customization_options[]" value="Finish" id="customization_options_finish">
                                                <label class="form-check-label" for="customization_options_finish">Finish</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="status" class="mb-2">Status</label>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input bg-success" id="status" name="status" value="1" <?php echo ($product_id > 0) ? '' : 'checked'; ?>>
                                            <label class="form-check-label" for="status"><?php echo ($product_id > 0) ? 'Active/Inactive' : 'Active'; ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-header bg-dark text-white">
                            <h3 class="card-title">Product Images</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="image" class="mb-2">Product Main Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        <div id="previewMainImage" class="mt-2" style="max-width: 100px; max-height: 100px;"></div>
                                        <div id="current_image_preview" class="mt-2"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="other_images" class="mb-2">Product Other Images (Max 3)</label>
                                        <input type="file" class="form-control" id="other_images" name="other_images[]" multiple accept="image/*">
                                        <div id="previewOtherImages" class="mt-2 d-flex flex-wrap"></div>
                                        <div id="current_other_images_preview" class="mt-2 d-flex flex-wrap"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm mt-3 mb-5"><?php echo ($product_id > 0) ? 'Update Product' : 'Save Product'; ?></button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
    $(document).ready(function () {
        $('.select2').select2();

        // Function to load product details for editing
        function loadProductDetails(productId) {
            $.ajax({
                url: '../ajax/products/get_product_details.php',
                type: 'POST',
                dataType: 'json',
                data: { product_id: productId },
                success: function (response) {
                    if (response.success) {
                        var product = response.data;
                        console.log(product); // Debugging: Check the product object in the console

                        $('#category_id').val(product.category_id).trigger('change'); // Trigger change to load subcategories

                        $('#name').val(product.name);
                        $('#code').val(product.code);
                        $('#brand_name').val(product.brand_name);
                        $('#short_description').val(product.short_description);
                        $('#dimensions').val(product.dimensions);
                        $('#packing_box_size').val(product.packing_box_size);
                        $('#packing_type').val(product.packing_type);
                        $('#weight').val(product.weight);
                        $('#cbm').val(product.cbm);
                        $('#loadability').val(product.loadability);
                        $('input[name="assembly_required"][value="' + product.assembly_required + '"]').prop('checked', true);
                        $('#assembly_tools').val(product.assembly_tools);
                        $('#price_per_20pcs').val(product.price_per_20pcs);
                        $('#primary_material').val(product.primary_material);
                        $('#secondary_material').val(product.secondary_material);
                        $('#finish_appearance').val(product.finish_appearance);
                        $('#color_variant').val(product.color_variant);
                        $('#status').prop('checked', product.status == 1);

                        // Handle subcategory
                        if (product.subcategory_id) {
                            $('#subcategory_id').val(product.subcategory_id);
                            // Trigger change to load sub subcategories
                            $('#subcategory_id').trigger('change');
                        }

                        // Handle sub sub category (set value after it loads)
                        if (product.subsubcategory_id) {
                            // Use a timeout to set the value after subcategories are loaded
                            setTimeout(function() {
                                $('#subsubcategory_id').val(product.subsubcategory_id);
                            }, 100); // Adjust timeout if needed
                        }

                        // Handle material select2
                        if (product.material && Array.isArray(product.material)) {
                            $('#material').val(product.material).trigger('change');
                        }

                        // Handle customization options checkboxes
                        if (product.customization_options) {
                            $.each(product.customization_options, function (index, option) {
                                $('input[name="customization_options[]"][value="' + option + '"]').prop('checked', true);
                            });
                        }

                        // Display current images
                        $('#current_image_preview').html('');
                        if (product.image) {
                            $('#current_image_preview').append('<img src="../../uploads/products/' + product.code + '/' + product.image + '" alt="Main Image" width="100"><br>');
                        }
                        $('#current_other_images_preview').html('');
                        if (product.other_images && product.other_images.length > 0) {
                            $.each(product.other_images, function (index, image) {
                                $('#current_other_images_preview').append('<img src="../../uploads/products/' + product.code + '/' + image + '" alt="Other Image ' + (index + 1) + '" width="100"> ');
                            });
                        }
                    } else {
                        toastr.error(response.message, 'Error!');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error loading product details:", error);
                    toastr.error("Error loading product details.", 'Error!');
                }
            });
        }

        // Load product details if product ID is in the URL
        var urlParams = new URLSearchParams(window.location.search);
        var productIdFromUrl = urlParams.get('id');
        if (productIdFromUrl) {
            loadProductDetails(productIdFromUrl);
        }

        // Load subcategories on category change
        $('#category_id').change(function () {
            var category_id = $(this).val();
            if (category_id) {
                $.ajax({
                    url: '../ajax/products/get_subcategories.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { category_id: category_id },
                    success: function (data) {
                        $('#subcategory_id').empty().append('<option value="">Select Sub Category</option>');
                        $.each(data, function (key, value) {
                            $('#subcategory_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                        $('#subsubcategory_id').empty().append('<option value="">Select Sub Sub Category</option>');
                    }
                });
            } else {
                $('#subcategory_id').empty().append('<option value="">Select Sub Category</option>');
                $('#subsubcategory_id').empty().append('<option value="">Select Sub Sub Category</option>');
            }
        });

        // Load sub-subcategories on subcategory change
        $('#subcategory_id').change(function () {
            var subcategory_id = $(this).val();
            if (subcategory_id) {
                $.ajax({
                    url: '../ajax/products/get_subsubcategories.php',
                    type: 'POST',
                    dataType: 'json',
                    data: { subcategory_id: subcategory_id },
                    success: function (data) {
                        $('#subsubcategory_id').empty().append('<option value="">Select Sub Sub Category</option>');
                        $.each(data, function (key, value) {
                            $('#subsubcategory_id').append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#subsubcategory_id').empty().append('<option value="">Select Sub Sub Category</option>');
            }
        });

        // Image preview for main image
        $('input[name="image"]').on('change', function () {
            $('#previewMainImage').empty();
            if (this.files && this.files[0]) {
                let reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewMainImage').html(`<img src="${e.target.result}" style="max-width: 100%; max-height: 100%;">`);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Image preview for other images
        $('input[name="other_images"]').on('change', function () {
            $('#previewOtherImages').empty();
            if (this.files) {
                for (let i = 0; i < Math.min(this.files.length, 3); i++) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $('#previewOtherImages').append(`<img src="${e.target.result}" style="max-width: 100px; max-height: 100px; margin-right: 5px;">`);
                    }
                    reader.readAsDataURL(this.files[i]);
                }
            }
        });

        $('#productForm').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: '../ajax/products/save_product.php', // Ensure this path is correct
        type: 'POST',
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                toastr.success(response.message, 'Success!');
                // Redirect to product list or clear the form
                window.location.href = 'view.php'; // Redirect to view page after save
                // OR $('#productForm')[0].reset(); // Clear the form
            } else {
                toastr.error(response.message, 'Error!');
            }
        },
        error: function (xhr, status, error) {
            console.error("Error saving product:", error);
            toastr.error("Error saving product.", 'Error!');
        }
    });
});
    });
</script>