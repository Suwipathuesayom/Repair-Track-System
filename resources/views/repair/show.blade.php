@extends('layouts.app')

@section('content')
    <style>
        .detail-container {
            max-width: 700px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            font-family: 'Sarabun', sans-serif;
        }

        .detail-container h2 {
            margin-bottom: 1rem;
            color: #333;
        }

        .detail-row {
            margin-bottom: 1rem;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
        }

        .attachment {
            margin-top: 1.5rem;
        }

        .attachment img,
        .attachment video {
            max-width: 100%;
            border-radius: 6px;
            margin-top: 10px;
        }

        .btn-back {
            display: inline-block;
            margin-top: 2rem;
            background-color: #6c757d;
            color: white;
            padding: 10px 16px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>

    <div class="detail-container">
        <h2>รายละเอียดการแจ้งซ่อม</h2>

        <div class="detail-row">
            <div class="detail-label">หัวข้อ:</div>
            <div>{{ $repair->title }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">รายละเอียด:</div>
            <div>{{ $repair->description }}</div>
        </div>


        <div class="detail-row">
            <div class="detail-label">ระดับความเร่งด่วน:</div>
            <div>{{ ucfirst($repair->urgency_level) }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">สถานะ:</div>
            <div>{{ $repair->status }}</div>
        </div>

        <div class="detail-row">
            <div class="detail-label">วันที่แจ้ง:</div>
            <div>{{ \Carbon\Carbon::parse($repair->created_at)->format('d/m/Y H:i') }}</div>
        </div>

        @if ($repair->attachment)
            <div class="attachment">
                <div class="detail-label">ไฟล์แนบ:</div>
                @if(str_starts_with($repair->attachment->file_type, 'image'))
                    <img src="{{ asset('storage/' . $repair->attachment->file_path) }}" alt="แนบไฟล์">
                @elseif(str_starts_with($repair->attachment->file_type, 'video'))
                    <video controls>
                        <source src="{{ asset('storage/' . $repair->attachment->file_path) }}"
                            type="{{ $repair->attachment->file_type }}">
                        เบราว์เซอร์ของคุณไม่รองรับการเล่นวิดีโอ
                    </video>
                @else
                    <a href="{{ asset('storage/' . $repair->attachment->file_path) }}" target="_blank">ดาวน์โหลดไฟล์แนบ</a>
                @endif
            </div>
        @endif

        <a href="{{ route('dashboard') }}" class="btn-back">← กลับ</a>


        @if($repair->logs && $repair->logs->count())
            <hr style="margin: 2rem 0;">
            <h3>ประวัติการดำเนินการ</h3>
            <ul style="list-style: none; padding-left: 0;">
                @foreach($repair->logs as $log)
                    <li style="margin-bottom: 1rem; background: #f9f9f9; padding: 1rem; border-radius: 8px;">
                        <strong>โดย:</strong> {{ $log->changedBy->name ?? '-' }} <br>
                        <strong>จาก:</strong> {{ $log->from_status }} → <strong>เป็น:</strong> {{ $log->to_status }} <br>
                        <strong>รายละเอียด:</strong> {{ $log->note }} <br>
                        <strong>เมื่อ:</strong> {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}
                    </li>
                @endforeach
            </ul>
        @endif

    </div>
@endsection