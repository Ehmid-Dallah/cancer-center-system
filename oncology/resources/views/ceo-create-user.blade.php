<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>إنشاء مستخدم جديد</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
            direction: rtl;
            padding: 30px;
        }
        form {
            background: #fff;
            padding: 20px;
            width: 400px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #444;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #2c3e50;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #1a252f;
        }
        .success {
            color: green;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<h2 style="text-align:center">إضافة مستخدم جديد</h2>

<form method="POST" action="{{ route('ceo.user.store') }}">
    @csrf

    <label for="name">الاسم:</label>
    <input type="text" name="name" required>

    <label for="email">البريد الإلكتروني:</label>
    <input type="email" name="email" required>

    <label for="password">كلمة المرور:</label>
    <input type="password" name="password" required>

    <button type="submit">إضافة المستخدم</button>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
</form>

</body>
</html>
