// public/scripts/sidebar.js
// Toggle sidebar visibility for mobile view
// This script runs after the DOM is loaded and safely checks for element existence.

document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('toggleSidebarMobile');
  const sidebar = document.getElementById('sidebar');

  if (!toggleBtn) {
    console.warn('Toggle button (toggleSidebarMobile) not found in DOM.');
    return;
  }
  if (!sidebar) {
    console.warn('Sidebar element with id "sidebar" not found in DOM.');
    return;
  }

  // Ensure the sidebar starts hidden on mobile (Tailwind classes handle this)
  // Attach click handler to toggle visibility
  toggleBtn.addEventListener('click', function () {
    // Toggle the hidden class (or use Tailwind's translate-x-0 / -translate-x-full for animation)
    if (sidebar.classList.contains('hidden')) {
      sidebar.classList.remove('hidden');
    } else {
      sidebar.classList.add('hidden');
    }
  });
});
