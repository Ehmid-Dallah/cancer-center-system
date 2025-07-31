<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة المريض</title>
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
</head>
<body>

    <nav> 
        <button onclick="showSection('personalInfo')">المعلومات الشخصية</button>
   

    </nav> 

    <div class="container">

        @if(session('success') || session('error'))
        <div id="alertModal" class="alert-modal">
            <div class="alert-content">
                <h3 style="color: {{ session('success') ? 'green' : 'red' }};">
                    {{ session('success') ?? session('error') }}
                </h3>
                <button onclick="document.getElementById('alertModal').style.display='none'">موافق</button>
            </div>
        </div>
        @endif

        <!-- القسم الرئيسي -->
        <div id="personalInfo" class="section active">
         <h2 class="welcome-message">مرحبًا بك يا {{ optional($patient)->first_name }} 👋</h2>

            <h3 class="welcome-subtitle">هذه بياناتك الشخصية المسجلة لدينا</h3>

            <div class="dashboard-cards">
                <div class="card">
                    <h2>الاسم الكامل:</h2>
                    <p>{{ $patient->first_name }} {{ $patient->last_name }}</p>
                </div>
                <div class="card">
                    <h2>المنطقة:</h2>
                    <p>{{ $patient->area }}</p>
                </div>
                <div class="card">
                    <h2>المركز الصحي:</h2>
                    <p>{{ $patient->center->name ?? 'غير معروف' }}</p>
                </div>
                <div class="card">
                    <h2>تاريخ التسجيل:</h2>
                    <p>{{ $patient->registration_date }}</p>
                </div>
                <div class="card">
                    <h2>رقم الهوية:</h2>
                    <p>{{ $patient->identity_number ?? '---' }}</p>
                </div>
                <div class="card">
                    <h2>رقم الهاتف:</h2>
                    <p>{{ $patient->phone1 ?? '---' }}</p>
                </div>
                <div class="card">
                    <h2>البريد الإلكتروني:</h2>
                    <p>{{ $patient->user->email ?? '---' }}</p>
                </div>
            </div>
        </div>

    </div>

    <script>
        function showSection(id) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(id).classList.add('active');
        }
    </script>

    <script src="{{ asset('js/employee.js') }}"></script>
</body>
</html>
