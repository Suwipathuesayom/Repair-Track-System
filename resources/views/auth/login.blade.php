<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ</title>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: white;
            padding: 2rem 2.5rem;
            border-radius: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 400px;
        }

        h2,
        h4 {
            text-align: center;
            color: #333;
            margin-bottom: 1.5rem;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
            color: #555;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .error-message {
            background-color: #ffe5e5;
            border: 1px solid #ff8888;
            color: #b30000;
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>ระบบแจ้งซ่อมคอมพิวเตอร์</h2>
        <h4>เข้าสู่ระบบ</h2>
            @if($errors->any())
                <div class="error-message">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <label for="email">อีเมล:</label>
                <input type="email" name="email" id="email" required>

                <label for="password">รหัสผ่าน:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">เข้าสู่ระบบ</button>
            </form>
    </div>
</body>

</html>