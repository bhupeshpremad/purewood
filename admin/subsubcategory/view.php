<?php include '../includes/header.php'; ?>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-2 px-0">
            <?php include '../includes/sidebar.php'; ?>
        </div>
        <div class="col-lg-10 pt-5 px-4">
            <div class="bg-black text-white p-2 mb-4 d-flex justify-content-between align-items-center">
                <h4 class="m-0">Sub-Subcategory List</h4>
                <a href="add.php" class="btn btn-primary">+ Add Sub-Subcategory</a>
            </div>

            <div class="bg-white p-3 shadow rounded-2">
                <table class="table table-bordered table-striped" id="subsubcatTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Category</th>
                            <th>Subcategory</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this sub-subcategory?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    let deleteId = null;

    fetchSubSubcategories();

    function fetchSubSubcategories() {
        $.ajax({
            url: '../ajax/subsubcategory/fetch-all-subsubcategories.php',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    let html = '';
                    response.data.forEach((item, index) => {
                        html += `<tr>
                            <td>${index + 1}</td>
                            <td><img src="../${item.image}" width="50" height="50" class="rounded" /></td>
                            <td>${item.name}</td>
                            <td>${item.slug}</td>
                            <td>${item.category_name}</td>
                            <td>${item.subcategory_name}</td>
                            <td>
                                <button class="btn btn-sm toggle-status ${item.status == 1 ? 'btn-success' : 'btn-danger'}" 
                                    data-id="${item.id}" data-status="${item.status}">
                                    ${item.status == 1 ? 'Active' : 'Inactive'}
                                </button>
                            </td>
                            <td>
                                <a href="add.php?id=${item.id}" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="${item.id}" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>`;
                    });
                    $('#subsubcatTable tbody').html(html);
                }
            }
        });
    }

    // Delete Modal
    $(document).on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        $('#deleteModal').modal('show');
    });

    $('#confirmDeleteBtn').on('click', function() {
        if (deleteId) {
            $.post('../ajax/subsubcategory/delete-subsubcategory.php', { id: deleteId }, function(res) {
                let data = JSON.parse(res);
                if (data.status) {
                    $('#deleteModal').modal('hide');
                    toastr.success(data.message || "Deleted successfully");
                    fetchSubSubcategories();
                } else {
                    toastr.error(data.message || "Failed to delete.");
                }
            });
        }
    });

    // Toggle Status
    $(document).on('click', '.toggle-status', function () {
        let id = $(this).data('id');
        let currentStatus = $(this).data('status');
        let newStatus = currentStatus == 1 ? 0 : 1;

        $.post('../ajax/subsubcategory/update-subsubcategory-status.php', { id: id, status: newStatus }, function (res) {
            let data = JSON.parse(res);
            if (data.status) {
                toastr.success("Status updated");
                fetchSubSubcategories();
            } else {
                toastr.error("Status update failed");
            }
        });
    });
});
</script>
