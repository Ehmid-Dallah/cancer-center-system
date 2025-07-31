<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير</title>
     <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

    <div class="profile-container">
        <div class="dropdown">
            <button onclick="toggleDropdown()">
                <img src="https://via.placeholder.com/40" alt="Profile">
                <span>{{ auth()->user()->name }}</span>
            </button>
            <div id="profileDropdown">
             <button class="edit-btn">
  <a href="{{ route('profile.edit') }}#update-password">تعديل كلمة المرور</a>
</button>

               <button >  <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   style="color: red;">تسجيل الخروج</a></button>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
     </div>
<nav>
    <button onclick="showSection('info')">  لوحة المعلومات</button>
    <button onclick="showSection('addUserForm')">اضافة رئيس قسم  </button>
    <button onclick="showSection('usersList')">عرض رؤساء الأقسام </button>
    <form class="ehmid" method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" style="background-color: #e74c3c;">تسجيل الخروج</button>
    </form>
</nav>

<div class="container">

   @if(session('success'))
    <div id="alertModal" class="alert-modal">
        <div class="alert-content">
            <h3>{{ session('success') }}</h3>
            <button onclick="closeAlert()">موافق</button>
        </div>
    </div>
@endif
@if(session('error'))
    <div id="alertModal" class="alert-modal">
        <div class="alert-content">
            <h3 style="color: red;">{{ session('error') }}</h3>
            <button onclick="closeAlert()">موافق</button>
        </div>
    </div>
@endif



<div id="info" class="section active">
    <h2 class="welcome-message">مرحبًا بكم في لوحة تحكم  المدير  </h2>
    <h3 class="welcome-subtitle">يمكنك من هنا إدارة المراكز، ومدراء المراكز ومتابعة كل شيء بكل سهولة!</h3>

<div class="dashboard-cards">
    <div class="card" >
        <h4>  رؤساء الأقسام</h4>
        <p>{{ $ceosCount }}</p>
    </div>
    <div class="card">
        <h4>رئيس قسم الموظفين 🧑‍💼</h4>
        <p>{{ $employeeCeoCount }}</p>
    </div>
    <div class="card">
        <h4>رئيس قسم الأطباء 🩺</h4>
        <p>{{ $doctorCeoCount }}</p>
    </div>
    <div class="card">
        <h4>رئيس قسم الصيادلة 💊</h4>
        <p>{{ $pharmacistCeoCount }}</p>
    </div>
</div>

</div>


    <div id="addUserForm" class="section ">
        <h2 style="text-align:center">إضافة رئيس قسم     </h2>
        <form method="POST" action="{{ route('admin.create.ceo') }}">
            @csrf
            <label>الاسم:</label>
            <input type="text" name="name" required>

            <label>البريد الإلكتروني:</label>
            <input type="email" name="email" required>

            <label>كلمة المرور:</label>
            <input type="password" name="password" required>

            <label>نوع المستخدم:</label>
            <select name="role" required>
                <option value="ceo_employee">رئيس قسم الموظفين</option>
                <option value="ceo_doctors">رئيس قسم الأطباء</option>
                <option value="ceo_pharmacists">رئيس قسم الصيادلة</option>
            </select>

            <button type="submit" class="submit-btn">إضافة رئيس القسم</button>
        </form>
    </div>

    <div id="usersList" class="section">
        <h2>المستخدمون التابعون لك</h2>
        <table>
    <thead>
        <tr>
            <th>الاسم</th>
            <th>البريد</th>
            <th>اسم المدير</th>
            <th>النوع</th>
            <th>الإجراءات</th> 
        </tr>
    </thead>
    <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->parent->name ?? 'غير معروف' }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    <!-- زر التعديل -->
                    <a href="{{ route('admin.edit.ceo', $user->id) }}" class="btn-edit">تعديل</a>

                    <!-- زر الحذف داخل فورم -->
                    <form method="POST" action="{{ route('admin.delete.ceo', $user->id) }}" style="display:inline;" onsubmit="return confirmDelete(event)">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">حذف</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

    </div>
    
</div>

<script src="{{ asset('js/admin.js') }}"></script>

</body>
</html>
