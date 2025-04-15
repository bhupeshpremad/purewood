<?php include('../includes/header.php'); ?>
<?php include '../../config/config.php'; ?>



<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-2 px-0">
      <?php include('../includes/sidebar.php'); ?>
    </div>

    <!-- Main Content -->
    <div class="col-lg-10 mt-5 px-4">
      <div class="d-flex bg-dark justify-content-between align-items-center mb-3 py-3 px-3 rounded">
        <h2 class="mb-0 text-white">Manage Categories</h2>
        <a href="add.php" class="btn btn-success">+ Add Category</a>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered text-center align-middle" id="categoryTable">
          <thead class="table-dark">
            <tr>
              <th>S.No</th>
              <th>Name</th>
              <th>Image</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $stmt = $conn->prepare("SELECT * FROM categories ORDER BY id DESC");
            $stmt->execute();
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $sn = 1;
            foreach ($categories as $row) {
            ?>
              <tr>
                <td><?= $sn++; ?></td>
                <td><?= htmlspecialchars($row['name']); ?></td>
                <td><img src="../uploads/category/<?= htmlspecialchars($row['image']); ?>" width="60"></td>
                <td>
                  <input type="checkbox" class="toggle-status" data-id="<?= $row['id']; ?>" <?= $row['status'] ? 'checked' : ''; ?> data-toggle="toggle" data-on="Active" data-off="Inactive" data-onstyle="success" data-offstyle="danger">
                </td>
                <td>
                  <a href="add.php?id=<?= $row['id']; ?>" class="text-info me-2"><i class="fas fa-edit"></i></a>
                  <a href="javascript:void(0);" class="text-danger delete-btn" data-id="<?= $row['id']; ?>"><i class="fas fa-trash-alt"></i></a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirm Delete</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this category?
        <input type="hidden" id="deleteId">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>


<!-- Footer -->
<?php include('../includes/footer.php'); ?>

<!-- Script for toggle and delete -->
<script>
  $(document).ready(function () {
    // Init toggle
    $('input[data-toggle="toggle"]').bootstrapToggle();

    // Toggle category status
    $('.toggle-status').change(function () {
      var id = $(this).data('id');
      var status = $(this).prop('checked') ? 1 : 0;

      $.ajax({
        url: '../ajax/category/update-category-status.php',
        type: 'POST',
        data: { id: id, status: status },
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            toastr.success(response.message);
          } else {
            toastr.error(response.message);
          }
        }
      });
    });

    // ✅ Toggle subcategory status
    $(document).on('change', '.subcategory-status-toggle', function () {
      const id = $(this).data('id');
      const status = $(this).is(':checked') ? 1 : 0;

      $.post('../ajax/subcategory/update-subcategory-status.php', { id, status }, function (res) {
        if (res.success) {
          toastr.success(res.message);
        } else {
          toastr.error(res.message);
        }
      }, 'json');
    });

    // Delete modal
    $('.delete-btn').click(function () {
      $('#deleteId').val($(this).data('id'));
      $('#deleteModal').modal('show');
    });

    // Confirm delete
    $('#confirmDelete').click(function () {
      var id = $('#deleteId').val();

      $.ajax({
        url: '../ajax/category/delete-category.php',
        type: 'POST',
        data: { id: id },
        dataType: 'json',
        success: function (response) {
          if (response.success) {
            toastr.success(response.message);
            $('#deleteModal').modal('hide');
            setTimeout(() => location.reload(), 1000);
          } else {
            toastr.error(response.message);
          }
        }
      });
    });
  });
</script>

