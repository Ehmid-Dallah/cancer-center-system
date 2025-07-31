<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل مستخدم</title>
    <link rel="stylesheet" href="{{ asset('css/ceo.css') }}">
</head>
<body>

<div class="container">
    <h2 style="text-align:center">تعديل بيانات المستخدم</h2>

    @if(session('error'))
        <p style="color:red;">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('ceo.user.update', $user->id) }}">
        @csrf
        @method('PUT')

        <label for="name">الاسم
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        </label>

        <label for="email">البريد الإلكتروني
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </label>

        <label for="password">كلمة المرور الجديدة (اختياري)
            <input type="password" name="password">
        </label>

        <button type="submit" class="submit-btn">تحديث</button>
    </form>

    <br>
    <a href="{{ route('ceo.dashboard') }}">🔙 العودة إلى لوحة التحكم</a>
</div>

</body>
</html>
