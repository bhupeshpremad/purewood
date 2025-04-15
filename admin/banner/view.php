<?php include('../includes/header.php'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 px-0">
            <?php include('../includes/sidebar.php'); ?>
        </div>

        <div class="col-lg-10 mt-4">
            <div class="bg-white rounded p-4 shadow border">
                <div class="bg-dark d-flex justify-content-between align-items-center mb-3">
                    <h2 class="text-white bg-dark p-2 rounded">View Banners</h2>
                    <a href="add.php" class="btn btn-success">Add New Banner</a>
                </div>

                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Alt Tag</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="bannerData">
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">Are you sure you want to delete this banner?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button id="confirmDelete" type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteId = null;

    function loadBanners() {
        fetch('../ajax/banner/fetch-all.php') // Ensure this file exists and returns banner data in JSON format
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('bannerData');
                tbody.innerHTML = '';

                data.forEach((banner, index) => {
                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${banner.name}</td>
                            <td>${banner.alt_tag || ''}</td>
                            <td>
                                ${banner.image ? `<img src="../uploads/banners/${banner.image}" width="60">` : ''}
                            </td>
                            <td>
                                <input type="checkbox" class="form-check-input status-toggle" data-id="${banner.id}" ${banner.status == 1 ? 'checked' : ''}>
                            </td>
                            <td>
                                <a href="add.php?id=${banner.id}" class="btn btn-sm btn-primary">Edit</a>
                                <button class="btn btn-sm btn-danger deleteBtn" data-id="${banner.id}" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete</button>
                            </td>
                        </tr>
                    `;
                });

                addEventListeners();
            });
    }

    function addEventListeners() {
        document.querySelectorAll('.status-toggle').forEach(btn => {
            btn.addEventListener('change', function () {
                const id = this.dataset.id;
                const status = this.checked ? 1 : 0;

                fetch('../ajax/banner/update-status.php', { // Ensure this file exists and handles status update
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${id}&status=${status}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                });
            });
        });

        document.querySelectorAll('.deleteBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                deleteId = this.dataset.id;
            });
        });
    }

    document.getElementById('confirmDelete').addEventListener('click', () => {
        fetch('../ajax/banner/delete-banner.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${deleteId}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                loadBanners(); // Reload banners after deletion
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                modal.hide();

                // Manually remove the backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
            } else {
                toastr.error(data.message);
            }
        });
    });

    loadBanners(); // Load banners on page load
</script>

<?php include('../includes/footer.php'); ?>