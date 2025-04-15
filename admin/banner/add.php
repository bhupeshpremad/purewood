<?php include('../includes/header.php'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 px-0">
            <?php include('../includes/sidebar.php'); ?>
        </div>

        <div class="col-lg-10 mt-4">
            <div class="bg-white rounded p-4 shadow border">
                <h2 id="form-title" class="text-xl font-semibold mb-4 text-white bg-dark p-2 rounded">Add Banner</h2>

                <form id="bannerForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="banner_id" value="">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Banner Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="alt_tag" class="form-label">Alt Tag</label>
                            <input type="text" class="form-control" id="alt_tag" name="alt_tag">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Banner Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
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
    const bannerId = urlParams.get('id');

    if (bannerId) {
        document.getElementById('form-title').textContent = "Edit Banner";
        document.getElementById('banner_id').value = bannerId;

        fetch(`../ajax/banner/get-banner.php?id=${bannerId}`) // Aapko yeh file banani hogi
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('name').value = data.banner.name;
                    document.getElementById('alt_tag').value = data.banner.alt_tag;

                    if (data.banner.image) {
                        document.getElementById('image-preview').innerHTML =
                            `<img src="../uploads/banners/${data.banner.image}" alt="Banner Image" class="img-thumbnail" width="100">`;
                    }

                    document.getElementById('status').checked = data.banner.status == 1;
                } else {
                    toastr.error("Failed to fetch banner data");
                }
            });
    }

    document.getElementById('bannerForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        if (!formData.has("status")) {
            formData.append("status", 0);
        }

        fetch('../ajax/banner/add-banner.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) { // Note: 'success' changed to 'status' in your add-banner.php
                toastr.success(data.message);
                setTimeout(() => {
                    document.getElementById('bannerForm').reset(); // Form reset
                    if (!bannerId) {
                        window.location.href = 'add.php'; // Stay on add page if adding
                    }
                }, 2000);
            } else {
                toastr.error(data.message || 'Failed to save banner');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error("An error occurred while saving the banner."); // Updated message
        });
    });
</script>
<?php include('../includes/footer.php'); ?>