document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.getElementById('sidebar');

  // Auto collapse on small screens
  if (window.innerWidth <= 768) {
      sidebar.classList.add('collapsed');
  }

  // Toggle function globally accessible
  window.toggleSidebar = function () {
      sidebar.classList.toggle('collapsed');
  };
});
