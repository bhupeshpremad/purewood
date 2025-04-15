<?php
    include './includes/header.php';

require_once __DIR__ . '/../config/config.php';
$baseUrl = AppConfig::baseUrl();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purewood - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-lg p-4 rounded-0" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <img src="<?= $baseUrl ?>/assets/images/Purewood-F-logo.svg" alt="Purewood Logo" class="img-fluid" style="max-height: 80px;">
        </div>

        <form id="loginForm" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control rounded-0" id="email" name="email" required placeholder="Enter email">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control rounded-0" id="password" name="password" required placeholder="Enter password">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn bg-black text-white fs-16 fw-500 px-4 rounded-0 text-uppercase btn-block">Login</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById("loginForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch("ajax/login.php", {
            method: "POST",
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === "success") {
                toastr.success(data.message);
                setTimeout(() => {
                    window.location.href = "dashboard.php";
                }, 1500);
            } else {
                toastr.error(data.message);
            }
        })
        .catch(err => {
            console.error(err);
            toastr.error("Something went wrong!");
        });
    });
</script>

<?php
      include './includes/header.php';

?>

