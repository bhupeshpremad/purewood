<?php include './includes/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-12 col-md-3 col-lg-2 bg-light p-0">
            <?php include './includes/sidebar.php'; ?>
        </div>

        <!-- Main Dashboard -->
        <div class="col-12 col-md-9 col-lg-10 py-4 px-4">
            <h1 class="mb-4 fw-bold">Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?> 👋</h1>

            <div class="row g-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body">
                            <h5 class="card-title">Total Products</h5>
                            <p class="fs-4">120</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body">
                            <h5 class="card-title">Categories</h5>
                            <p class="fs-4">12</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <h5 class="card-title">Orders</h5>
                            <p class="fs-4">45</p>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body">
                            <h5 class="card-title">Users</h5>
                            <p class="fs-4">10</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './includes/footer.php'; ?>
