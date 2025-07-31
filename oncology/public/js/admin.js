 function showSection(sectionId) {
        document.querySelectorAll('.section').forEach(section => {
            section.classList.remove('active');
        });
        document.getElementById(sectionId).classList.add('active');
    }
// في superadmin.js
function toggleDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
}

window.onclick = function(event) {
    if (!event.target.closest('.dropdown')) {
        const dropdown = document.getElementById('profileDropdown');
        if (dropdown) {
            dropdown.style.display = 'none';
        }
    }
};
    function closeAlert() {
        const modal = document.getElementById('alertModal');
        modal.style.display = 'none';
    }
      function confirmDelete(event) {
        if (!confirm('هل أنت متأكد أنك تريد حذف هذا المستخدم؟')) {
            event.preventDefault(); // إلغاء الحذف
            return false;
        }
        return true;
    }