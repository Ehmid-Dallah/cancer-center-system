<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة تحكم الطبيب</title>
    <link rel="stylesheet" href="{{ asset('css/doctor.css') }}">
</head>
<body>
    <script>
    const defaultSection = "{{ session('activeSection', 'info') }}";
</script>

<nav class="sidebar">
    <div class="menu">
        <h2>لوحة التحكم</h2>
        <ul>
            <li><button class="nav-btn" data-target="info">لوحة المعلومات</button></li>
            <li><button class="nav-btn" data-target="addPrescription">إضافة وصفة</button></li>
            <li><button class="nav-btn" data-target="prescriptionList">سجل الوصفات</button></li>
        </ul>
    </div>
    <div class="logout-container">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">تسجيل خروج</button>
        </form>
    </div>
</nav>


<div class="container">
    <div class="content">

        @if(session('success') || session('error'))
            <div id="alertModal" class="alert-modal">
                <div class="alert-content">
                    <h3 style="color: {{ session('success') ? 'green' : 'red' }}">
                        {{ session('success') ?? session('error') }}
                    </h3>
                    <button onclick="document.getElementById('alertModal').style.display='none'">موافق</button>
                </div>
            </div>
        @endif

        <!-- لوحة المعلومات -->
        <div id="info" class="section active">
            <h2>مرحبًا بك يا د. {{ auth()->user()->name }}!</h2>
            <h3>يمكنك إدارة الوصفات الخاصة بالمرضى بكل سهولة</h3>
            <div class="dashboard-cards">
                 <div class="card" >
                <h2>عدد الوصفات:</h2>
                <p>{{ $prescriptions->count() }}</p>
            </div>
        </div>
        </div>

        <!-- إضافة وصفة -->
<div id="addPrescription" class="section">
    <h2>البحث عن مريض</h2>
    <form method="POST" action="{{ route('prescriptions.search') }}">
        @csrf
        <input type="text" name="search_key" placeholder="أدخل اسم المريض أو رقم الملف" required>
        <button type="submit">بحث</button>
    </form>

  

    <!-- عرض بيانات المريض -->
 @php
    $patient_id = session()->pull('patient_id');
    $patient_name = session()->pull('patient_name');
    $patient_file = session()->pull('patient_file');
@endphp

@if($patient_id)
    <div class="patient-info">
        <p><strong>الاسم:</strong> {{ $patient_name }}</p>
        <p><strong>رقم الملف:</strong> {{ $patient_file }}</p>
    </div>
        <!-- نموذج تسجيل وصفة -->
        <form method="POST" action="{{ route('prescriptions.store') }}" style="margin-top: 20px;">
            @csrf
           <input type="hidden" name="patient_id" value="{{ $patient_id }}">


            <label>اسم العلاج:
                <input type="text" name="drug_name" required>
            </label>

            <label>الكمية:
                <input type="number" name="quantity" required>
            </label>

            <label>ملاحظات:
                <textarea name="notes"></textarea>
            </label>

            <label>تاريخ التسجيل:
                <input type="date" name="prescribed_at" required>
            </label>

            <button type="submit">حفظ الوصفة</button>
        </form>
    @endif
</div>


        <!-- سجل الوصفات -->
        <div id="prescriptionList" class="section" >
            <h2>سجل الوصفات</h2>
            <table>
                <thead>
                    <tr>
                          <th>رقم الوصفة</th>
                          <th>اسم المريض</th> <!-- أضف هذا -->
                        <th>اسم العلاج</th>
                        <th>الكمية</th>
                        
                      
                        <th>اسم الطبيب</th>
                        <th>تاريخ التسجيل</th>
                         <th>الإجراءات</th> <!-- الحقل الجديد -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($prescriptions as $prescription)
                    <tr>
                        <td>{{ $prescription->id }}</td> <!-- رقم الوصفة -->
                            <td>
                    {{ optional($prescription->patient)->first_name }}
                    {{ optional($prescription->patient)->last_name }}
                </td>
                        <td>{{ $prescription->drug_name }}</td>
                        <td>{{ $prescription->quantity }}</td>
                      
                      
                  <td>{{ $prescription->doctor->name ?? 'غير معروف' }}</td>
                        <td>{{ $prescription->prescribed_at }}</td>
<td>
    @if($prescription->is_confirmed)
        @php
            $expiresAt = \Carbon\Carbon::parse($prescription->confirmed_at)->addHours(24);
            $now = \Carbon\Carbon::now();
            $remainingSeconds = $expiresAt->greaterThan($now) ? $expiresAt->diffInSeconds($now) : 0;
        @endphp

        @if($remainingSeconds > 0)
            <div class="countdown" data-seconds="{{ $remainingSeconds }}" style="margin-bottom:5px;"></div>

            <!-- زر التعديل -->
            <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="edit-btn">تعديل</a>

            <!-- زر الحذف -->
            <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')" class="delete-btn">حذف</button>
            </form>
        @else
            <button class="disabled-btn" disabled>تم التأكيد</button>
        @endif
    @else
        <!-- زر التأكيد -->
        <form action="{{ route('prescriptions.confirm', $prescription->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('PATCH')
            <button type="submit" onclick="return confirm('هل تريد تأكيد وصف الوصفة للمريض؟')" class="confirm-btn">تأكيد</button>
        </form>

        <!-- زر التعديل -->
        <a href="{{ route('prescriptions.edit', $prescription->id) }}" class="edit-btn">تعديل</a>

        <!-- زر الحذف -->
        <form action="{{ route('prescriptions.destroy', $prescription->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')" class="delete-btn">حذف</button>
        </form>
    @endif
</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div> <!-- end .content -->
</div> <!-- end .container -->


<script src="{{ asset('js/doctor.js') }}"></script>



</body>
</html>
