<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم المدير</title>
      <link rel="stylesheet" href="{{ asset('css/ceo.css') }}">
</head>
<body>


    @php
    $roleLabels = [
        'ceo_employee' => ['عرض الموظفين', 'إضافة موظف'],
        'ceo_doctors' => ['عرض  الأطباء', 'إضافة طبيب'],
        'ceo_pharmacists' => ['عرض الصيادلة', 'إضافة صيدلاني'],
    ];

    $labelView = $roleLabels[$ceo->role][0] ?? 'عرض المستخدمين';
    $labelAdd  = $roleLabels[$ceo->role][1] ?? 'إضافة مستخدم';
@endphp

<nav>
  <button onclick="showSection('info')">  لوحة المعلومات</button>
    <button onclick="showSection('usersList')">{{ $labelView }}</button>
    <button onclick="showSection('addUserForm')">{{ $labelAdd }}</button>
    <form class="ehmid" method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" style="background-color: #e74c3c;">تسجيل الخروج</button>
    </form>
 </nav>

    <div class="container">
   
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
    <h2 class="welcome-message">مرحبًا بك في لوحة تحكم رئيس القسم يا {{ $ceo->name }}</h2>
    <h3 class="welcome-subtitle">يمكنك من هنا متابعة المستخدمين التابعين لك والتحكم الكامل بهم!</h3>

    @php
        $count = 0;
        $label = 'عدد المستخدمين التابعين لك 👥';

        if ($ceo->role === 'ceo_employee') {
            $count = $users->where('role', 'employee')->count();
            $label = 'عدد الموظفين التابعين لك 🧑‍💼';
        } elseif ($ceo->role === 'ceo_doctors') {
            $count = $users->where('role', 'doctor')->count();
            $label = 'عدد الأطباء التابعين لك 🩺';
        } elseif ($ceo->role === 'ceo_pharmacists') {
            $count = $users->where('role', 'pharmacists')->count();
            $label = 'عدد الصيادلة التابعين لك 💊';
        }
    @endphp

    <div class="dashboard-cards">
        <div class="card">
            <h4>{{ $label }}</h4>
            <p>{{ $count }}</p>
        </div>
    </div>
 </div>


    {{-- قسم عرض المستخدمين --}}
    <div id="usersList" class="section ">
        <h2>قائمة المستخدمين</h2>
        <table>
           <thead>
    <tr>
        <th>الاسم</th>
        <th>البريد</th>
        <th>الدور</th>
        <th>الإجراءات</th> <!-- جديد -->
    </tr>
</thead>
<tbody>
@foreach ($users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->role }}</td>
        <td>
            <form action="{{ route('ceo.user.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete()">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete">حذف</button>
            </form>
          <a href="{{ route('ceo.user.edit', $user->id) }}" class="btn-edit">تعديل</a>

        </td>
    </tr>
@endforeach
</tbody>

        </table>
    </div>

    {{-- نموذج إضافة مستخدم --}}
    <div id="addUserForm" class="section">
       
           <h2 style="text-align:center">إضافة مستخدم جديد      </h2>
    
        <form method="POST" action="{{ route('ceo.user.store') }}">
            @csrf
       
            
                          <label for="name">اسم المستخدم 
                      
                         <input type="text" name="name"  required></label>
                        
                        
                     
                       
                   
                   <label for="email">البريد الإلكتروني
                         <input type="email" name="email" required></label>
                         
                       
                      <label for="password">كلمة المرور
                         <input type="password"name="password" required></label>
                       
                        
                            
                            <button type="submit" class="submit-btn" >إضافة مستخدم</button>
                        
                      
               
                
        </form>
    </div>

 </div>

<script src="{{ asset('js/ceo.js') }}"></script>

</body>
</html>
