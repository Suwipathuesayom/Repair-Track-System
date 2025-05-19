@extends('layouts.app')

@section('content')
    <style>
        .form-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            max-width: 600px;
            margin: 2rem auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            font-family: 'Sarabun', sans-serif;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.4rem;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .error-message {
            background-color: #ffe5e5;
            border: 1px solid #ff8888;
            padding: 10px;
            border-radius: 6px;
            color: #b30000;
            margin-bottom: 1rem;
        }

        .btn-submit {
            background-color: #007bff;
            color: white;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="form-container">
        <h2>แจ้งซ่อมใหม่</h2>

        @if($errors->any())
            <div class="error-message">
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('repair.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="title">หัวข้อปัญหา:</label>
                <input type="text" name="title" id="title" required>
            </div>

            <div class="form-group">
                <label for="description">รายละเอียด:</label>
                <textarea name="description" id="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="urgency_level">ระดับความเร่งด่วน:</label>
                <select name="urgency_level" id="urgency_level" required>
                    <option value="">-- กรุณาเลือก --</option>
                    <option value="critical">เร่งด่วนมาก (Critical)</option>
                    <option value="high">เร่งด่วน (High)</option>
                    <option value="normal">ปกติ (Normal)</option>
                </select>
            </div>

            <div class="form-group">
                <label for="attachment">แนบรูปภาพหรือวิดีโอ (ถ้ามี):</label>
                <input type="file" name="attachment" id="attachment">
            </div>

            <div style="text-align: center;">
                <button type="submit" class="btn-submit">ส่งคำขอแจ้งซ่อม</button>
            </div>
        </form>
    </div>
@endsection