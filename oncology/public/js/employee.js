// التنقل بين الأقسام
function showSection(sectionId) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.style.display = 'block';
    }
}

// ربط التنقل مع القوائم الجانبية
document.querySelectorAll('.sidebar ul li a').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        const target = this.getAttribute('href').substring(1);
        if (target !== '') {
            showSection(target);
        }
    });
});

// دالة تعديل بيانات مريض (تعبيء النموذج بالبيانات)
function editPatient(id) {
    const row = document.querySelector(`tr[data-patient-id="${id}"]`);
    if (!row) return;

    const columns = row.querySelectorAll('td');

    // تعبئة بيانات النموذج
    document.getElementById('editFirstName').value = columns[1].textContent.trim();
    document.getElementById('editLastName').value = columns[2].textContent.trim();
    document.getElementById('editArea').value = columns[3].textContent.trim();
    document.getElementById('editCenterId').value = row.getAttribute('data-center-id');
    document.getElementById('editRegistrationDate').value = row.getAttribute('data-registration-date');

    // ضبط action في النموذج ليرسل البيانات لتعديل المريض
    const form = document.getElementById('editForm');
    form.action = `/patients/update/${id}`;
    form.querySelector('input[name="_method"]').value = 'POST'; // أو 'PUT' حسب الراوت


    function closeAlert() {
        const modal = document.getElementById('alertModal');
        modal.style.display = 'none';
    } 
    // إظهار نموذج التعديل
    document.getElementById('editPatientForm').style.display = 'block';
}
document.addEventListener('DOMContentLoaded', function () {
    const centerSelect = document.getElementById('healthCenter');
    const hiddenArea = document.getElementById('hiddenArea');

    centerSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const area = selectedOption.getAttribute('data-area');
        hiddenArea.value = area;
    });
});
