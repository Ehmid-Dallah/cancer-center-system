// دالة تسجيل الخروج
function logout() {
    alert('تم تسجيل الخروج!');
    window.location.href = '/login'; // غير المسار حسب نظامك
}

// دالة التنقل بين الأقسام
function showSection(sectionId) {
    const sections = document.querySelectorAll('.section');
    sections.forEach(section => {
        section.style.display = 'none';
        section.classList.remove('active');
    });

    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.style.display = 'block';
        targetSection.classList.add('active');
    }
}


// ربط الروابط مع دالة التنقل
document.querySelectorAll('.sidebar ul li a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const target = this.getAttribute('href').substring(1);
        if (target !== '') {
            showSection(target);
        }
    });
});

    document.addEventListener('DOMContentLoaded', function () {
        const defaultSection = "{{ session('active_section', 'dashboard') }}";
        showSection(defaultSection);
    });

