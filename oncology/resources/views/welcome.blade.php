<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>المنظومة الوطنية لمراكز علاج الأورام</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f8f8;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .logo {
            width:200px;
            height: auto;
            margin-bottom: 20px;
        }

        h1 {
            color: #2c3e50;
            font-size: 28px;
        }

        .links {
            margin-top: 30px;
        }

        a {
            text-decoration: none;
            color: white;
            background-color: #3498db;
            padding: 10px 20px;
            margin: 10px;
            border-radius: 8px;
            font-size: 16px;
        }

        a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
@if (session('success'))
    <div style="padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
@endif

    <!-- الصورة -->
    <img src="{{ asset('images/logo.jpeg') }}" alt="شعار المنظومة" class="logo">

    <!-- العنوان -->
    <h1>مرحبًا بكم في المنظومة الوطنية لمراكز علاج الأورام</h1>

    <!-- الروابط -->
    <div class="links">
        @if (Route::has('login'))
           @auth
    @php
        $role = auth()->user()->role;
    @endphp

    @if ($role === 'superadmin')
        <a href="{{ url('/superadmin/dashboard') }}">الذهاب للوحة التحكم</a>
    @elseif ($role === 'admin')
        <a href="{{ url('/admin/dashboard') }}">الذهاب للوحة التحكم</a>
    @elseif ($role === 'ceo_doctors' || $role === 'ceo_pharmacists' || $role === 'ceo_employee')
        <a href="{{ url('/ceo/dashboard') }}">الذهاب للوحة التحكم</a>
    @elseif ($role === 'employee')
        <a href="{{ route('employee.dashboard') }}">الذهاب للوحة التحكم</a>
    @elseif ($role === 'doctor')
        <a href="{{ route('doctor.dashboard') }}">الذهاب للوحة التحكم</a>
    @elseif ($role === 'pharmacists')
        <a href="{{ route('pharmacists.dashboard') }}">الذهاب للوحة التحكم</a>
    @else
        <a href="{{ url('/dashboard') }}">الذهاب للوحة التحكم</a>
    @endif
@else
    <a href="{{ route('login') }}">تسجيل الدخول</a>
<!--
    @if (Route::has('register'))
        <a href="{{ route('register') }}">تسجيل حساب جديد</a>
    @endif-->
@endauth
@endif
    </div>

</body>
</html>
