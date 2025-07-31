<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الموظف</title>
    <link rel="stylesheet" href="{{ asset('css/employee.css') }}">
</head>
<body>


    <nav > 
            
              <button onclick="showSection('info')"> لوحة المعلومات</button>
               <button onclick="showSection('addUserForm')">إضافة مريض</button>
               <button onclick="showSection('usersList')">سجل المرضى</button>
        <div class="logout-container">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="logout-btn">تسجيل خروج</button>
            </form>
        </div>
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


    <div id="info" class="section active">
        
            <h2 class="welcome-message">مرحبًا بكم في لوحة تحكم  الموظف  </h2>
    <h3 class="welcome-subtitle">يمكنك من هنا إدارة بيانات المريض , وتسجيله في المركز!</h3>
          <div class="dashboard-cards">
    <div class="card" >
                <h2>عدد المرضى المسجلين :</h2>
                 <p >{{ $patients->count() }}</p>
            </div>
          </div>
    </div>

        <!-- إضافة مريض -->
        <div id="addUserForm" class="section ">
            
             <h2 style="text-align:center">إضافة مريض جديد      </h2>
            <form id="patientForm" action="{{ route('patients.store') }}" method="POST">
    @csrf
    <fieldset>
        <legend>البيانات الشخصية للمريض</legend>

        <div class="form-group">
            <label for="firstName">الاسم الأول</label>
            <input type="text" name="first_name" id="firstName" required>
        </div>
<div class="form-group">
        <label for="fatherName">اسم الأب</label>
        <input type="text" name="father_name" id="fatherName">
    </div>
        <div class="form-group">
            <label for="lastName">اللقب</label>
            <input type="text" name="last_name" id="lastName" required>
        </div>
        

    <div class="form-group">
        <label for="motherName">اسم الأم</label>
        <input type="text" name="mother_name" id="motherName">
    </div>

    <div class="form-group">
        <label for="nationality">الجنسية</label>
        <input type="text" name="nationality" id="nationality">
    </div>

    <div class="form-group">
        <label for="identityType">نوع الهوية</label>
        <select name="identity_type" id="identityType">
            <option value="بطاقة شخصية">بطاقة شخصية</option>
            <option value="جواز سفر">جواز سفر</option>
        </select>
    </div>

    <div class="form-group">
        <label for="identityNumber">رقم الهوية</label>
        <input type="text" name="identity_number" id="identityNumber">
    </div>

    <div class="form-group">
        <label for="gender">الجنس</label>
        <select name="gender" id="gender">
            <option value="ذكر">ذكر</option>
            <option value="أنثى">أنثى</option>
        </select>
    </div>
<div class="form-group">
    <label for="birthDate">تاريخ الميلاد</label>
    <input type="date" name="birth_date" id="birthDate">
</div>
    <div class="form-group">
        <label for="birthPlace">مكان الميلاد</label>
        <input type="text" name="birth_place" id="birthPlace">
    </div>

    <div class="form-group">
        <label for="residence">مكان الإقامة</label>
        <input type="text" name="residence" id="residence">
    </div>

    <div class="form-group">
        <label for="phone1">رقم الهاتف الأول</label>
        <input type="text" name="phone1" id="phone1">
    </div>

    <div class="form-group">
        <label for="phone2">رقم الهاتف الثاني</label>
        <input type="text" name="phone2" id="phone2">
    </div>
    <div class="form-group">
    <label for="email">البريد الإلكتروني للمريض</label>
    <input type="email" name="email" id="email" required>
</div>
    </fieldset>

    <fieldset>
        <legend>البيانات المتعلقة بالمركز</legend>

        <div class="form-group">
            <label for="area">المنطقة</label>
            <select name="area" id="editArea" required>
                <option value="المنطقة الجنوبية">المنطقة الجنوبية</option>
                <option value="المنطقة الشرقية">المنطقة الشرقية</option>
                <option value="المنطقة الغربية">المنطقة الغربية</option>
            </select>
        </div>

        <div class="form-group">
            <label for="healthCenter">المركز الصحي</label>
            <select name="center_id" id="healthCenter" required>
                <option value="">اختر المركز الصحي</option>
                @foreach($centers as $center)
                    <option value="{{ $center->id }}" data-area="{{ $center->area }}">{{ $center->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="registrationDate">تاريخ التسجيل</label>
            <input type="date" name="registration_date" id="registrationDate" required>
        </div>
        <div class="form-group">
        <label for="infectionDate">تاريخ الإصابة بالمرض</label>
        <input type="date" name="infection_date" id="infectionDate">
    </div>

    </fieldset>

    <button type="submit">حفظ المريض</button>
</form>

             </div>

        <!-- سجل المرضى -->
       <div id="usersList" class="section">
            <h2>سجل المرضى</h2>
            <table id="patientsTable">
                <thead>
                    <tr>
                        <th>رقم الملف</th>
                        <th>الاسم الأول</th>
                        <th>الاسم الأخير</th>
                        <th>المنطقة</th>
                        <th>رقم المركز الصحي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $patient)
                    <tr data-patient-id="{{ $patient->id }}"
                        data-center-id="{{ $patient->center_id }}"
                        data-registration-date="{{ $patient->registration_date }}">
                        <td>{{ $patient->file_number }}</td>
                        <td>{{ $patient->first_name }}</td>
                        <td>{{ $patient->last_name }}</td>
                        <td>{{ $patient->area }}</td>
                        <td>{{ $patient->center_id  ?? 'غير معروف' }}</td>
                        <td>
                            <button class="edit-btn" onclick="editPatient({{ $patient->id }})">تعديل</button>

                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn" onclick="return confirm('هل أنت متأكد من حذف هذا المريض؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- نموذج تعديل -->
            <div id="editPatientForm" style="display:none; margin-top:20px;">
                <h2>تعديل بيانات المريض</h2>
                <form id="editForm" method="POST">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    <label>الاسم الأول:
                        <input type="text" name="first_name" id="editFirstName" required>
                    </label>
                    <label>اللقب:
                        <input type="text" name="last_name" id="editLastName" required>
                    </label>
                    <label>المنطقة:
                        <select name="area" id="editArea" required>
                            <option value="المنطقة الجنوبية">المنطقة الجنوبية</option>
                            <option value="المنطقة الشرقية">المنطقة الشرقية</option>
                            <option value="المنطقة الغربية">المنطقة الغربية</option>
                        </select>
                    </label>
                    <label>المركز الصحي:
                        <select name="center_id" id="editCenterId" required>
                            @foreach($centers as $center)
                                <option value="{{ $center->id }}">{{ $center->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label>تاريخ التسجيل:
                        <input type="date" name="registration_date" id="editRegistrationDate" required>
                    </label>
                    <button type="submit">تحديث</button>
                </form>
            </div>
        </section>
    </main>
</div>

<script>
function editPatient(id) {
    const row = document.querySelector(`tr[data-patient-id="${id}"]`);
    if (!row) return;

    const columns = row.querySelectorAll('td');

    document.getElementById('editFirstName').value = columns[1].textContent.trim();
    document.getElementById('editLastName').value = columns[2].textContent.trim();
    document.getElementById('editArea').value = columns[3].textContent.trim();
    document.getElementById('editCenterId').value = row.getAttribute('data-center-id');
    document.getElementById('editRegistrationDate').value = row.getAttribute('data-registration-date');

    const form = document.getElementById('editForm');
    form.action = `/patients/update/${id}`;
    form.querySelector('input[name="_method"]').value = 'POST';

    document.getElementById('editPatientForm').style.display = 'block';
}

</script>

<script src="{{ asset('js/employee.js') }}"></script>
<script src="../shared/storage.js"></script>
</body>
</html>
