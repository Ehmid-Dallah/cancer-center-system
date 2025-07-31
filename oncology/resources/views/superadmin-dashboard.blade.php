<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم مدير الهيئة الوطنية </title>
    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}">
  
</head>
<body>
  <!-- ✅ مربع البروفايل -->
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
    <button onclick="showSection('info')">لوحة المعلومات</button>
    <button onclick="showSection('centersList')">عرض المراكز</button>
    <button onclick="showSection('addCenterForm')">إضافة مركز</button>
    <button onclick="showSection('adminsList')">عرض مدراء المراكز</button>
    <button onclick="showSection('addAdminForm')">إضافة مدير جديد</button>
    
    <form class="ehmid" method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" style="background-color: #e74c3c;">تسجيل الخروج</button>
    </form>
</nav>




@if(session('success') || session('error'))
<div id="alertModal" class="alert-modal">
    <div class="alert-content">
        @if(session('success'))
            <h3 style="color: green;">{{ session('success') }}</h3>
        @elseif(session('error'))
            <h3 style="color: red;">{{ session('error') }}</h3>
        @endif
        <button onclick="closeAlert()">موافق</button>
    </div>
</div>
@endif




   

    <div id="info" class="section active">
    <h2 class="welcome-message">مرحبًا بك يا  {{$user->name}} في لوحة تحكم  مدير الهيئة الوطنية لمكافحة السرطان</h2>
    <h3 class="welcome-subtitle">يمكنك من هنا إدارة المراكز، ومدراء المراكز ومتابعة كل شيء بكل سهولة!</h3>

    <div class="dashboard-cards">
        <div class="card">
            <h4>عدد المراكز</h4>
            <p id="centerCount">{{ $centers->count() }}</p>
        </div>
        <div class="card">
            <h4>عدد المدراء</h4>
            <p id="adminCount">{{ $admins->count() }}</p>
        </div>
    </div>
</div>

    
    <div id="centersList" class="section">
        <h2>قائمة المراكز</h2>
        <table>
            <thead>
                <tr>
                    <th>اسم المركز</th>
                    <th>اسم المدير</th>
                    <th>تاريخ الإنشاء</th>
                    <th>التحكم</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($centers as $center)
                    <tr>
                        <td>{{ $center->name }}</td>
                        <td>{{ $center->user->name ?? 'غير معروف' }}</td>
                        <td>{{ $center->created_at->format('Y-m-d') }}</td>
                        <td>
                            <button type="submit" class="edit-btn">  <a href="{{ route('superadmin.center.edit', $center->id) }}" 
                             >تعديل</a></button>
                        
                            <form action="{{ route('superadmin.center.destroy', $center->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn" onclick="return confirm('هل أنت متأكد من حذف هذا المركز؟')">حذف</button>
                            </form>
                        </td>
                        
                       
                        </td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="addCenterForm" class="section">
        <form method="POST"  action="{{ route('superadmin.center.store') }}">
            @csrf
            <fieldset>
            <legend> :بيانات المركز  </legend>
                <label for="name">اسم المركز:</label>
                <input type="text" name="name" required>
                
                <label for="area">المنطقة:</label>
                <select name="area" required>
                    <option value="">اختر المنطقة</option>
                    <option value="المنطقة الجنوبية">المنطقة الجنوبية</option>
                    <option value="المنطقة الشرقية">المنطقة الشرقية</option>
                    <option value="المنطقة الغربية">المنطقة الغربية</option>
                    <option value="المنطقة الوسطى">المنطقة الوسطى</option>
                </select>
           
                </fieldset><br>
                
                <fieldset>
            <legend> :بيانات المدير </legend>
            <label for="admin_name">اسم المدير:</label>
            <input type="text" name="admin_name" required>
        
            <label for="admin_email">البريد الإلكتروني للمدير:</label>
            <input type="email" name="admin_email" required>
        
            <label for="admin_password">كلمة المرور:</label>
            <input type="password" name="admin_password" required></fieldset>
        
            <button type="submit" class="submit-btn">إضافة المركز والمدير</button>
        </form>
        
    </div>




    <div id="adminsList" class="section">
        <h2>قائمة مدراء المراكز</h2>
        <table>
            <thead>
                <tr>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>الدور</th>
                    <th>التحكم</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admins as $admin)
                    <tr>
                        <td>{{ $admin->name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ $admin->role }}</td>
                        <td>
                            <button type="submit" class="edit-btn"><a href="{{ route('superadmin.admin.edit', $admin->id) }}" class="btn btn-primary btn-sm">تعديل</a></button>
                        
                            <form action="{{ route('superadmin.admin.destroy', $admin->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn" onclick="return confirm('هل أنت متأكد من حذف هذا المدير')">حذف</button>
                            </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div id="addAdminForm" class="section">
        <h2 style="text-align:center">إضافة مدير جديد</h2>
        <form method="POST" action="{{ route('superadmin.admin.store') }}">
            @csrf
            <fieldset>
                <legend>بيانات المدير:</legend>
            <label for="name">الاسم:</label>
            <input type="text" name="name" required>

            <label for="email">البريد الإلكتروني:</label>
            <input type="email" name="email" required>

            <label for="password">كلمة المرور:</label>
            <input type="password" name="password" required>
            </fieldset>
            <button type="submit" class="submit-btn">إضافة المدير</button>
        </form>
    </div>

</div>

<script src="{{ asset('js/superadmin.js') }}"></script>




</body>
</html>
