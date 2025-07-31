document.addEventListener('DOMContentLoaded', function () {
    const sectionId = typeof defaultSection !== 'undefined' ? defaultSection : 'info';

    // أخفي كل الأقسام
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
    });

    // أظهر القسم الأساسي
    const target = document.getElementById(sectionId);
    if (target) target.classList.add('active');

    // التنقل بين الأقسام
    document.querySelectorAll('.nav-btn').forEach(button => {
        button.addEventListener('click', function () {
            const sectionTarget = this.getAttribute('data-target');
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionTarget).classList.add('active');
        });
    });
});
document.addEventListener('DOMContentLoaded', () => {
    const countdowns = document.querySelectorAll('.countdown');

    countdowns.forEach((el) => {
        let seconds = parseInt(el.dataset.seconds);

        const interval = setInterval(() => {
            if (seconds <= 0) {
                el.textContent = "انتهى الوقت";
                location.reload(); // تحديث الواجهة تلقائياً
                clearInterval(interval);
                return;
            }

            let hrs = Math.floor(seconds / 3600);
            let mins = Math.floor((seconds % 3600) / 60);
            let secs = seconds % 60;

            el.textContent = `متبقي: ${hrs}س ${mins}د ${secs}ث`;

            seconds--;
        }, 1000);
    });
});

