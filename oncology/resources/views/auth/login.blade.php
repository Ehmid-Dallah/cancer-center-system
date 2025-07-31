<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول - المنظومة الوطنية</title>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f5f6fa;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-box {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 350px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 75%;
            padding: 12px;
            background-color: #2ba8fb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            display: block;       /* لجعل الزر عنصر بلوك */
    margin: 20px auto 0;  /* هذا هو التوسيط الأفقي */

        }

        button:hover {
            background-color: #3498db;
        }

        .links {
            text-align: center;
            margin-top: 10px;
        }

        .links a {
            color: #2c3e50;
            text-decoration: none;
            font-size: 14px;
        }

        .message {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .login-img {
    display: block;
    margin: 0 auto 20px auto;
    width: 100px; /* يمكنك تغيير الحجم حسب أبعاد الصورة */
    height: auto;
}

    </style>
</head>
<body>

<div class="login-box">
     <!-- الصورة -->
    <img src="{{ asset('images/image.jpeg') }}" alt="تسجيل الدخول" class="login-img">
    <h2>تسجيل الدخول</h2>

    <!-- Session Status -->
    @if (session('status'))
        <div class="message">{{ session('status') }}</div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="message">
            <ul style="padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">البريد الإلكتروني:</label>
        <input id="email" type="email" name="email" required autofocus>

        <label for="password">كلمة المرور:</label>
        <input id="password" type="password" name="password" required>

        <button type="submit">تسجيل الدخول</button>
    </form>
     <!--
    <div class="links">
        <a href="{{ route('register') }}">إنشاء حساب جديد</a>
    </div>-->
</div>

</body>
</html>
