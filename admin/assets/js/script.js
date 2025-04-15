document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');

  // Collapse sidebar if screen is small
  if (sidebar && window.innerWidth <= 768) {
    sidebar.classList.add('collapsed');
  }

  // Toggle sidebar manually
  window.toggleSidebar = function () {
    if (sidebar) {
      sidebar.classList.toggle('collapsed');
    }
  };

  // Admin Login AJAX
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();

      const formData = new FormData(this);

      fetch("ajax/login.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.json())
        .then(data => {
          if (data.status === "success") {
            toastr.success(data.message);
            setTimeout(() => window.location.href = "dashboard.php", 1500);
          } else {
            toastr.error(data.message);
          }
        })
        .catch(() => {
          toastr.error("Something went wrong. Try again.");
        });
    });
  }

  // Optional: Logout button (if needed)
  const logoutBtn = document.getElementById("logoutBtn");
  if (logoutBtn) {
    logoutBtn.addEventListener("click", () => {
      fetch("ajax/logout.php")
        .then(() => window.location.href = "index.php");
    });
  }
});
