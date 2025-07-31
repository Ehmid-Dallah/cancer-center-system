<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تعديل الملف الشخصي</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .card {
            background-color: #fff;
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>

    <div class="container">
        <h2>تعديل الملف الشخصي</h2>

        <!-- تعديل الاسم والبريد -->
        <div class="card">
            <form method="POST" action="/profile/update-info">
                <!-- csrf -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <label for="name">الاسم:</label>
                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>

                <label for="email">البريد الإلكتروني:</label>
                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>

                <button type="submit">تحديث المعلومات</button>
            </form>
        </div>

        <!-- تعديل كلمة المرور -->
        <div class="card" id="update-password">
            <form method="POST" action="/profile/update-password">
                <!-- csrf -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="PUT">

                <label for="current_password">كلمة المرور الحالية:</label>
                <input type="password" id="current_password" name="current_password" required>

                <label for="password">كلمة المرور الجديدة:</label>
                <input type="password" id="password" name="password" required>

                <label for="password_confirmation">تأكيد كلمة المرور:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>

                <button type="submit">تحديث كلمة المرور</button>
            </form>
        </div>

        <!-- حذف الحساب -->
        <div class="card">
            <form method="POST" action="/profile/delete">
                <!-- csrf -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">

                <label for="confirm_password">تأكيد كلمة المرور لحذف الحساب:</label>
                <input type="password" id="confirm_password" name="password" required>

                <button type="submit" style="background-color: red;">حذف الحساب</button>
            </form>
        </div>
    </div>

    <!-- تمرير تلقائي إلى كلمة المرور -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (window.location.hash === '#update-password') {
                const element = document.getElementById('update-password');
                if (element) {
                    element.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>

</body>
</html>
