 function showSection(sectionId) {
        document.querySelectorAll('.section').forEach((section) => {
            section.classList.remove('active');
        });

        document.getElementById(sectionId).classList.add('active');
    }
     function closeAlert() {
        const modal = document.getElementById('alertModal');
        modal.style.display = 'none';
    }
    function confirmDelete() {
    return confirm("هل أنت متأكد من حذف هذا المستخدم؟");
}

function editUser(user) {
    document.getElementById('editUserForm').style.display = 'block';
    showSection('editUserForm');

    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_password').value = '';

    const form = document.getElementById('editForm');
    form.action = `/ceo/update-user/${user.id}`;
}