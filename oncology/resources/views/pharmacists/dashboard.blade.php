<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة تحكم الصيادلة</title>
    <link rel="stylesheet" href="{{ asset('css/pharmacists.css') }}">
</head>
<body>

<div class="container">
    <!-- الناف بار -->
    <nav class="sidebar">
        <div class="menu">
            <h2>لوحة التحكم</h2>
            <ul>
                <li><a href="#dashboard">لوحة المعلومات</a></li>
                <li><a href="#add-patient">إضافة وصفة</a></li>
                <li><a href="#patients-list">قائمة الوصفات</a></li>
                <li><a href="#add-drug">إضافة الأدوية</a></li>
                <li><a href="#drugs-list">قائمة الأدوية</a></li>
            </ul>
        </div>
        <div class="logout-container">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="logout-btn">تسجيل خروج</button>
        </form>
    </div>
    </nav>
    
    <!-- محتوى الصفحة -->
    <main class="content">
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
        <section id="dashboard" class="section active">
            <h1>مرحباً بك في لوحة التحكم</h1>
            <b>اختر من القائمة لإدارة السجلات الطبية للمرضى المرضى</b><br><br><br><br><br><br>
        
            <!-- مربع عدد الوصفات -->
        <div class="stats-container">
    <div id="statsBox" class="stats-box">
        <h2>عدد الوصفات المضافة: <span id="prescriptionCount">{{ $prescriptionCount }}</span></h2>
    </div>
    <div id="drugStatsBox" class="stats-box">
        <h2>عدد أصناف الأدوية: <span id="drugCount">{{ $drugCount }}</span></h2>
    </div>
</div>


            
        </section>
        

        
        <!-- إضافة وصفى -->
       <section id="add-patient" class="section" style="display:none;">
    <h1>تسجيل وصفة جديدة</h1>

    @php
    session()->keep([
        'patient_id',
        'patient_name',
        'patient_file',
        'prescription_id',
        'prescription_notes',
        'prescription_patient_file',
    ]);
@endphp


    <!-- مربع البحث عن المريض -->
    <h2>البحث عن مريض</h2>
    <form method="POST" action="{{ route('pharmacists.searchPatient') }}">
        @csrf
        <input type="text" name="search_key" placeholder="أدخل اسم المريض أو رقم الملف" required>
        <button type="submit">بحث مريض</button>
    </form>

   @if(session('patient_id'))
    <div class="patient-info" style="margin-top: 20px;">
        <p><strong>اسم المريض:</strong> {{ session('patient_name') }}</p>
        <p><strong>رقم الملف:</strong> {{ session('patient_file') }}</p>
    </div>
@endif


    <!-- مربع البحث عن الوصفة -->
    <h2 style="margin-top: 30px;">البحث عن وصفة</h2>
    <form method="POST" action="{{ route('pharmacists.searchPrescription') }}">
        @csrf
        <input type="text" name="prescription_key" placeholder="أدخل رقم الوصفة" required>
        <button type="submit">بحث وصفة</button>
    </form>

   @if(session('prescription_id'))
    <div class="prescription-info" style="margin-top: 20px;">
        <p><strong>رقم الوصفة:</strong> {{ session('prescription_id') }}</p>
        <p><strong>ملاحظات:</strong> {{ session('prescription_notes') }}</p>
          <p><strong>اسم العلاج:</strong> {{ session(' prescription_drug_name') }}</p>
         <p><strong>الكمية:</strong> {{ session(' prescription_quantity') }}</p>
        <p><strong>رقم ملف المريض:</strong> {{ session('prescription_patient_file') }}</p>
    </div>



        <!-- نموذج صرف الوصفة -->
        
        @if(session('prescription_id') || isset($dispensation))
    <form method="POST" action="{{ isset($dispensation) ? route('dispensations.update', $dispensation->id) : route('dispensations.store') }}" style="margin-top: 20px;">
        @csrf
        @if(!isset($dispensation))
            <input type="hidden" name="prescription_id" value="{{ session('prescription_id') }}">
        @else
            <p><strong>رقم الوصفة:</strong> {{ $dispensation->prescription->id }}</p>
        @endif

        <label>اسم الدواء:
            <input type="text" name="drug_name" value="{{ $dispensation->drug_name ?? '' }}" required>
        </label>

        <label>الكمية:
            <input type="number" name="quantity" value="{{ $dispensation->quantity ?? '' }}" required>
        </label>

        <label>تاريخ الصرف:
            <input type="date" name="dispensed_at" value="{{ isset($dispensation) ? $dispensation->dispensed_at : '' }}" required>
        </label>

        <label>ملاحظات:
            <textarea name="notes">{{ $dispensation->notes ?? '' }}</textarea>
        </label>

        <button type="submit">{{ isset($dispensation) ? 'تحديث الصرف' : 'صرف الوصفة' }}</button>
    </form>
@endif

    @endif
</section>

        
        <!-- سجل المرضى -->
        <section id="patients-list" class="section" style="display:none;">
            <h1>سجل المرضى</h1>
            <table id="patientsTable">
                <thead>
                    <tr>
                        <th>اسم المريض</th>
                        <th>اسم الطبيب</th>
                        <th>اسم العلاج</th>
                        <th>الكمية</th>
                        <th>تاريخ التسجيل</th>
                        <th>اسم الصيدلي</th> 
                        <th>الإجراءات</th>
                    </tr>
                </thead>
               <tbody>
    @foreach($dispensations as $dispensation)
        <tr>
            <td>{{ $dispensation->prescription->patient->first_name ?? 'غير معروف' }} {{ $dispensation->prescription->patient->last_name ?? '' }}</td>
            <td>{{ $dispensation->prescription->doctor->name ?? 'غير معروف' }}</td>
            <td>{{ $dispensation->drug_name }}</td>
            <td>{{ $dispensation->quantity }}</td>
            <td>{{ $dispensation->dispensed_at }}</td>
            <td>{{ $dispensation->pharmacist->name ?? 'غير معروف' }}</td>
           <td>
    <!-- زر تعديل -->
    <a href="{{ route('dispensations.edit', $dispensation->id)  }}">
        <button class="edit-btn">تعديل</button>
    </a>

    <!-- زر حذف -->
    <form method="POST" action="{{ route('dispensations.destroy', $dispensation->id) }}" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من حذف هذه العملية؟');">
        @csrf
        <button type="submit" class="delete-btn">حذف</button>
    </form>
</td>

        </tr>
    @endforeach
</tbody>



            </table>
        </section>

        <section id="add-drug" class="section" style="display:none;">
            <h1>تسجيل دواء جديدة</h1>
          <h1>{{ isset($drug) ? 'تعديل الدواء' : 'تسجيل دواء جديد' }}</h1>
<form method="POST" action="{{ isset($drug) ? route('drugs.update', $drug->id) : route('drugs.store') }}">
    @csrf
    <label>اسم الدواء:
        <input type="text" name="name" value="{{ $drug->name ?? '' }}" required>
    </label>

    <label>الكمية:
        <input type="number" name="quantity" value="{{ $drug->quantity ?? '' }}" required>
    </label>

    <label>الشركة المصنعة:
        <input type="text" name="company" value="{{ $drug->company ?? '' }}" required>
    </label>

    <label>الدولة:
        <input type="text" name="country" value="{{ $drug->country ?? '' }}" required>
    </label>

    <label>تاريخ الصلاحية:
        <input type="date" name="expiration_date" value="{{ isset($drug) ? $drug->expiration_date : '' }}" required>
    </label>

    <button type="submit">{{ isset($drug) ? 'تحديث' : 'حفظ' }}</button>
</form>


        </section>
        <section id="drugs-list" class="section" style="display:none;">
            <h1>سجل المرضى</h1>
            <table id="drugsTable">
                <thead>
                    <tr>
                        <th>اسم الدواء</th>
                        <th>الكمية</th>
                        <th>الشركة المصنعة</th>
                        <th>الدولة</th>
                        <th> تاريخ الصلاحية </th>
                        <th>اسم الصيدلي</th>

                        <th>الإجراءات</th>
                       
                    </tr>
                </thead>
                <tbody>
    @foreach($drugs as $drug)
        <tr>
            <td>{{ $drug->name }}</td>
            <td>{{ $drug->quantity }}</td>
            <td>{{ $drug->company }}</td>
            <td>{{ $drug->country }}</td>
            <td>{{ $drug->expiration_date }}</td>
            <td>{{ $drug->pharmacist->name ?? 'غير معروف' }}</td>

           <td>
    <!-- زر تعديل -->
    <a href="{{ route('drugs.edit', $drug->id) }}">
        <button class="edit-btn">تعديل</button>
    </a>

    <!-- زر حذف -->
    <form method="POST" action="{{ route('drugs.destroy', $drug->id) }}" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟');">
        @csrf
        <button type="submit" class="delete-btn">حذف</button>
    </form>
</td>

        </tr>
    @endforeach
</tbody>



            </table>
        </section>
    </main>
</div>

<script src="{{ asset('js/pharmacists.js') }}"></script>
<script src="../shared/storage.js"></script>
</body>
</html>
