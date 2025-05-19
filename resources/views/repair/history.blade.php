@extends('layouts.app')

@section('content')
    <style>
        .container {
            max-width: 1000px;
            margin: 2rem auto;
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            font-family: 'Sarabun', sans-serif;
        }

        h2 {
            margin-bottom: 1rem;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #f0f0f0;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            color: white;
        }

        .badge.normal {
            background-color: #28a745;
        }

        .badge.high {
            background-color: #ffc107;
            color: #000;
        }

        .badge.critical {
            background-color: #dc3545;
        }

        .badge.cancelled {
            background-color: #6c757d;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 13px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            margin-right: 5px;
        }

        .btn:hover {
            opacity: 0.9;
        }
    </style>

    <div class="container">
        <h2>ประวัติการแจ้งซ่อม</h2>

        @if($repairs->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>หัวข้อ</th>
                        <th>เร่งด่วน</th>
                        <th>สถานะ</th>
                        <th>วันที่แจ้ง</th>
                        <th>ดู</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($repairs as $repair)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $repair->title }}</td>
                            <td><span class="badge {{ $repair->urgency_level }}">{{ ucfirst($repair->urgency_level) }}</span></td>
                            <td><span class="badge {{ $repair->status }}">{{ ucfirst($repair->status) }}</span></td>
                            <td>{{ $repair->created_at->format('d/m/Y H:i') }}</td>
                            <td><a href="{{ route('repair.show', $repair->id) }}" class="btn">ดู</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>ยังไม่มีรายการแจ้งซ่อม</p>
        @endif
    </div>
@endsection