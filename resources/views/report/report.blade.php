@extends('layouts.app')

@section('content')
    <head>
        <meta charset="UTF-8">
        <style>
            @font-face {
                font-family: 'THSarabun';
                src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format("truetype");
                font-weight: normal;
                font-style: normal;
            }

            body {
                font-family: 'THSarabun', DejaVu Sans, sans-serif;
                font-size: 16px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 1px solid #ddd;
                padding: 8px;
            }

            th {
                background-color: #f0f0f0;
            }
        </style>
    </head>

    <div style="font-family: 'Sarabun', sans-serif; padding: 20px; background: white;">
        <a href="{{ route('report.export_pdf', $repair->id) }}"
        style="display: inline-block; margin-top: 20px; padding: 10px 16px; background-color: #343a40; color: white; text-decoration: none; border-radius: 6px;">
            📄 Export PDF
        </a>

        <h2 style="text-align: center; margin-bottom: 30px;">รายงานการแจ้งซ่อม</h2>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
            <tr>
                <td><strong>เลขที่แจ้งซ่อม:</strong></td>
                <td>#{{ $repair->id }}</td>
            </tr>
            <tr>
                <td><strong>หัวข้อ:</strong></td>
                <td>{{ $repair->title }}</td>
            </tr>
            <tr>
                <td><strong>ผู้แจ้ง:</strong></td>
                <td>{{ $repair->user->name }}</td>
            </tr>
            <tr>
                <td><strong>สถานะปัจจุบัน:</strong></td>
                <td>
                    @php
                        $statusLabels = [
                            'pending' => 'รอดำเนินการ',
                            'in_progress' => 'กำลังดำเนินการ',
                            'waiting_info' => 'รอข้อมูลเพิ่มเติม',
                            'senior_required' => 'ส่งต่อช่างอาวุโส',
                            'returned_to_junior' => 'ส่งกลับช่างระดับต้น',
                            'waiting_parts_approval' => 'รออนุมัติอุปกรณ์',
                            'completed' => 'เสร็จสิ้น',
                            'cancelled' => 'ยกเลิกแล้ว',
                        ];
                    @endphp
                    {{ $statusLabels[$repair->status] ?? $repair->status }}
                </td>
            </tr>
        </table>

        <h4 style="margin-bottom: 10px;">ประวัติการดำเนินการ:</h4>
        <table style="width: 100%; border-collapse: collapse; border: 1px solid #ddd;">
            <thead style="background-color: #f0f0f0;">
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px;">จากสถานะ</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">ไปยังสถานะ</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">รายละเอียด</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">โดย</th>
                    <th style="border: 1px solid #ddd; padding: 8px;">เวลา</th>
                </tr>
            </thead>
            <tbody>
                @foreach($repair->logs as $log)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $statusLabels[$log->from_status] ?? $log->from_status }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ $statusLabels[$log->to_status] ?? $log->to_status }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $log->note }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">{{ $log->changedBy->name }}</td>
                        <td style="border: 1px solid #ddd; padding: 8px;">
                            {{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection