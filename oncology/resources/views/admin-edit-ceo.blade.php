<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل بيانات رئيس القسم</title>
    <link rel="stylesheet" href="{{ asset('css/ceo.css') }}">
</head>
<body>
    <h2>تعديل بيانات رئيس القسم</h2>
    <form method="POST" action="{{ route('admin.update.ceo', $ceo->id) }}">
        @csrf
        @method('PUT')

        <label>الاسم:</label>
        <input type="text" name="name" value="{{ $ceo->name }}" required>

        <label>البريد الإلكتروني:</label>
        <input type="email" name="email" value="{{ $ceo->email }}" required>

        <label for="password">كلمة المرور الجديدة (اختياري)
            <input type="password" name="password">
        </label>

        <button type="submit">تحديث البيانات</button>
    </form>
</body>
</html>
