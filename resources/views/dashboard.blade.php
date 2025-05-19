@include('components.parts-request-modal')

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 2rem;
            color: #333;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 1rem 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .welcome {
            font-size: 1.5rem;
            color: #2c3e50;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            background-color: #3498db;
            border: none;
            color: white;
            padding: 10px 16px;
            font-size: 14px;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn.logout {
            background-color: #e74c3c;
        }

        .btn:hover {
            background-color: #2980b9;
        }

        .btn.logout:hover {
            background-color: #c0392b;
        }

        .table-container {
            background-color: #ffffff;
            padding: 1.5rem;
            margin-top: 2rem;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        table th,
        table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
        }

        table th {
            background-color: #eaf4fc;
            color: #34495e;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: bold;
            color: white;
        }

        .badge.normal {
            background-color: #2ecc71;
        }

        .badge.high {
            background-color: #f1c40f;
            color: #2c3e50;
        }

        .badge.critical {
            background-color: #e74c3c;
        }

        .badge.cancelled {
            background-color: #95a5a6;
        }

        .btn-disabled {
            background-color: #bdc3c7;
            color: #fff;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .btn-small {
            padding: 6px 12px;
            font-size: 13px;
            border-radius: 6px;
            margin: 3px 2px;
            display: inline-block;
            background-color: #3498db;
            color: white;
            text-align: center;
            border: none;
            cursor: pointer;
        }

        .btn-small:hover {
            background-color: #2980b9;
        }


        .action-group {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 5px;
        }

        form.action-inline {
            display: inline;
        }
    </style>
</head>

<body>

    <div class="header"
        style="background-color: #ffffff; padding: 1rem 2rem; border-radius: 12px; box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05); display: flex; justify-content: space-between; align-items: center;">
        <div class="welcome" style="font-size: 1.5rem; color: #2c3e50;">
            ยินดีต้อนรับ, {{ auth()->user()->name }}
        </div>
        <div class="actions" style="display: flex; gap: 10px;">
            <a href="{{ route('repair.create') }}" class="btn"
                style="background-color: #3498db; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                แจ้งซ่อมใหม่
            </a>
            <a href="{{ route('repair.history') }}" class="btn"
                style="background-color: #5cb85c; color: white; padding: 10px 16px; border-radius: 6px; text-decoration: none; font-size: 14px;">
                ดูประวัติการแจ้งซ่อม
            </a>
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="btn"
                    style="background-color: #e74c3c; color: white; padding: 10px 16px; border-radius: 6px; border: none; cursor: pointer; font-size: 14px;">
                    ออกจากระบบ
                </button>
            </form>
        </div>
    </div>


    <div class="table-container">
        <h3>รายการแจ้งซ่อมของคุณ</h3>
        @if($repairs->count())
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>หัวข้อ</th>
                        <th>ระดับความเร่งด่วน</th>
                        <th>สถานะ</th>
                        <th>วันที่แจ้ง</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($repairs as $repair)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $repair->title }}</td>
                            <td><span class="badge {{ $repair->urgency_level }}">{{ ucfirst($repair->urgency_level) }}</span>
                            </td>
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

                                    $isUser = auth()->user()->role->name === 'user';

                                    $userHiddenStatuses = ['senior_required', 'returned_to_junior', 'waiting_parts_approval'];
                                    $displayStatus = $isUser && in_array($repair->status, $userHiddenStatuses)
                                        ? 'รอดำเนินการ'
                                        : ($statusLabels[$repair->status] ?? $repair->status);
                                @endphp

                                {{ $displayStatus }}
                            </td>


                            <td>{{ \Carbon\Carbon::parse($repair->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('repair.show', $repair->id) }}" class="btn btn-small">ดู</a>

                                @if(auth()->user()->role->name === 'user')
                                    <form action="{{ route('repair.cancel', $repair->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-small {{ $repair->status === 'cancelled' ? 'btn-disabled' : 'logout' }}"
                                            @if($repair->status !== 'pending') disabled @endif
                                            onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการยกเลิกการแจ้งซ่อมนี้?')">
                                            ยกเลิกการแจ้งซ่อม
                                        </button>
                                    </form>
                                @endif

                                @if(in_array(auth()->user()->role->name, ['technician']))
                                    <form method="POST" action="{{ route('repair.update_status', $repair->id) }}"
                                        style="margin-top: 0.5rem;">
                                        @csrf
                                        <button name="action" value="accept" class="btn btn-small"
                                            style="background-color: #28a745;">รับเรื่อง</button>
                                        <button type="button" class="btn btn-small" style="background-color: #6c757d; color: #000;"
                                            onclick="openRequestInfoModal({{ $repair->id }})">
                                            ขอข้อมูล
                                        </button>
                                        <button name="action" value="forward_to_senior" class="btn btn-small"
                                            style="background-color: #6f42c1;">ส่งต่ออาวุโส</button>
                                        <button name="action" value="complete" class="btn btn-small"
                                            style="background-color: #20c997;">เสร็จสิ้น</button>
                                    </form>
                                @endif

                                @if(auth()->user()->role->name === 'technician_senior')
                                    <button type="button" class="btn btn-small"
                                        onclick="openSeniorModal({{ $repair->id }}, 'take_over')">
                                        รับเรื่องต่อ
                                    </button>
                                    <button type="button" class="btn btn-small"
                                        onclick="openSeniorModal({{ $repair->id }}, 'log_fix')">
                                        บันทึกการแก้ไข
                                    </button>
                                    <button type="button" class="btn btn-small"
                                        onclick="openSeniorModal({{ $repair->id }}, 'send_back_to_junior')">
                                        ส่งกลับช่างระดับต้น
                                    </button>
                                    <button type="button" class="btn btn-small" onclick="openPartsModal({{ $repair->id }})">
                                        ขอเบิกอะไหล่
                                    </button>
                                    <button type="button" class="btn btn-small"
                                        onclick="openSeniorModal({{ $repair->id }}, 'complete')">
                                        แก้ไขเสร็จสิ้น
                                    </button>
                                @endif
                                @if(auth()->user()->role->name === 'it_manager')
                                    <button type="button" class="btn btn-small"
                                        onclick="openManagerModal({{ $repair->id }}, 'assign')">
                                        มอบหมายงาน
                                    </button>
                                    <button type="button" class="btn btn-small"
                                        onclick="openManagerModal({{ $repair->id }}, 'approve_parts')">
                                        อนุมัติอุปกรณ์
                                    </button>
                                    <a href="{{ route('report.report', $repair->id) }}" class="btn btn-small">
                                        ดูรายงาน
                                    </a>
                                    {{-- <a href="{{ route('report.export_pdf', $repair->id) }}" class="btn btn-small"
                                        style="background-color: #343a40;">
                                        Export PDF
                                    </a> --}}
                                @endif
                                @if(auth()->user()->role->name === 'it_manager')
                                    <form action="{{ route('repair.set_urgency', $repair->id) }}" method="POST"
                                        style="margin-top: 1rem;">
                                        @csrf
                                        <label for="urgency_level">ระดับความเร่งด่วน:</label>
                                        <select name="urgency_level" id="urgency_level">
                                            <option value="normal" {{ $repair->urgency_level === 'normal' ? 'selected' : '' }}>ปกติ
                                            </option>
                                            <option value="high" {{ $repair->urgency_level === 'high' ? 'selected' : '' }}>เร่งด่วน
                                            </option>
                                            <option value="critical" {{ $repair->urgency_level === 'critical' ? 'selected' : '' }}>
                                                เร่งด่วนมาก</option>
                                        </select>
                                        <button type="submit" class="btn btn-small">อัปเดต</button>
                                    </form>
                                @endif


                            </td>
                        </tr>
                        @if(auth()->user()->role->name === 'user' && $repair->logs->count())
                            <tr>
                                <td colspan="6" style="background-color:#f9f9f9; border-top:1px solid #ccc;">
                                    <strong>Notes</strong>
                                    <ul style="margin:0.5rem 0 0 1rem; padding:0;">
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
                                        @foreach($repair->logs as $log)
                                            @if(in_array($log->from_status, ['pending', 'in_progress']) && in_array($log->to_status, ['in_progress', 'waiting_info']))
                                                <li>
                                                    <span style="font-weight:bold;">{{ $log->changedBy->name }}</span>
                                                    {{ $statusLabels[$log->from_status] ?? $log->from_status }} →
                                                    {{ $statusLabels[$log->to_status] ?? $log->to_status }}
                                                    @if($log->note) — <em>{{ $log->note }}</em> @endif
                                                    <span style="color:gray;">
                                                        ({{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }})
                                                    </span>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endif

                        @if(auth()->user()->role->name !== 'user' && $repair->logs->count())
                            <tr>
                                <td colspan="6" style="background-color:#f9f9f9; border-top:1px solid #ccc;">
                                    <strong>ประวัติการดำเนินการ:</strong>
                                    <ul style="margin:0.5rem 0 0 1rem; padding:0;">
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
                                        @foreach($repair->logs as $log)
                                            <li>
                                                <span style="font-weight:bold;">{{ $log->changedBy->name }}</span>
                                                {{ $statusLabels[$log->from_status] ?? $log->from_status }}
                                                →
                                                {{ $statusLabels[$log->to_status] ?? $log->to_status }}
                                                @if($log->note) — <em>{{ $log->note }}</em> @endif
                                                <span style="color:gray;">
                                                    ({{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') }})
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @endif


                    @endforeach
                </tbody>
            </table>
        @else
            <p>ยังไม่มีรายการแจ้งซ่อม</p>
        @endif

        <div id="requestInfoModal"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:1000;">
            <div
                style="background:white; max-width:500px; margin:10% auto; padding:20px; border-radius:10px; position:relative;">
                <h3>ขอข้อมูลเพิ่มเติมจากผู้แจ้ง</h3>
                <form id="requestInfoForm" method="POST" style="margin-top: 1rem;">
                    @csrf
                    <input type="hidden" name="repair_id" id="modal_repair_id">
                    <textarea name="note" rows="4" placeholder="โปรดระบุรายละเอียด..."
                        style="width:100%;  border-radius:6px; border:1px solid #ccc;" required></textarea>
                    <div style="margin-top: 1rem; text-align:right;">
                        <button type="button" onclick="closeRequestInfoModal()"
                            style="background-color:#ff4545; border:none; padding:8px 16px; border-radius:6px; margin-right: 10px;">ยกเลิก</button>
                        <button type="submit"
                            style="background-color:#ffc107; border:none; padding:8px 16px; border-radius:6px;">ส่งคำขอ</button>
                    </div>
                </form>
            </div>
        </div>


        <div id="seniorModal"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:1000;">
            <div
                style="background:white; max-width:500px; margin:10% auto; padding:20px; border-radius:10px; position:relative;">
                <h3 id="seniorModalTitle">บันทึกการดำเนินการ</h3>
                <form id="seniorForm" method="POST">
                    @csrf
                    <textarea name="note" rows="4" placeholder="รายละเอียด..." required
                        style="width:100%; border:1px solid #ccc; border-radius:6px;"></textarea>
                    <div style="text-align:right; margin-top:1rem;">
                        <button type="button" onclick="closeSeniorModal()"
                            style="background-color: #ccc; padding:8px 16px; border-radius:6px;">ยกเลิก</button>
                        <button type="submit"
                            style="background-color: #007bff; color:white; padding:8px 16px; border-radius:6px;">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="managerModal"
            style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:1000;">
            <div
                style="background:white; max-width:500px; margin:10% auto; padding:20px; border-radius:10px; position:relative;">
                <h3 id="managerModalTitle">จัดการคำร้อง</h3>
                <form id="managerForm" method="POST">
                    @csrf

                    <div id="technicianSelectWrapper" style="margin-bottom: 1rem; display: none;">
                        <label for="technician_id">เลือกช่าง:</label>
                        <select name="technician_id" id="technician_id"
                            style="width: 100%; padding: 8px; border-radius: 6px; border: 1px solid #ccc;">
                            @foreach($technicians as $tech)
                                <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <textarea name="note" rows="4" placeholder="รายละเอียด..." required
                        style="width:100%; border:1px solid #ccc; border-radius:6px;"></textarea>

                    <div style="text-align:right; margin-top:1rem;">
                        <button type="button" onclick="closeManagerModal()"
                            style="background-color: #ccc; padding:8px 16px; border-radius:6px;">ยกเลิก</button>
                        <button type="submit"
                            style="background-color: #007bff; color:white; padding:8px 16px; border-radius:6px;">บันทึก</button>
                    </div>
                </form>

            </div>
        </div>



    </div>

</body>
<script>
    const actionTitles = {
        take_over: "รับเรื่องต่อ",
        log_fix: "บันทึกการแก้ไข",
        send_back_to_junior: "ส่งกลับช่างระดับต้น",
        request_parts: "ร้องขออุปกรณ์",
        complete: "แก้ไขเสร็จสิ้น"
    };

    function openRequestInfoModal(repairId) {
        document.getElementById('modal_repair_id').value = repairId;
        document.getElementById('requestInfoForm').action = `/repair/${repairId}/log-action`;
        document.getElementById('requestInfoModal').style.display = 'block';
    }

    function closeRequestInfoModal() {
        document.getElementById('requestInfoModal').style.display = 'none';
    }

    function openSeniorModal(repairId, actionValue) {
        const form = document.getElementById('seniorForm');
        form.action = `/repair/${repairId}/senior-action`;

        let existingInput = document.getElementById('senior_action_input');
        if (existingInput) existingInput.remove();

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'action';
        input.value = actionValue;
        input.id = 'senior_action_input';
        form.appendChild(input);

        const titleMap = {
            take_over: "รับเรื่องต่อ",
            log_fix: "บันทึกการแก้ไข",
            send_back_to_junior: "ส่งกลับช่างระดับต้น",
            request_parts: "ร้องขออุปกรณ์",
            complete: "แก้ไขเสร็จสิ้น"
        };

        document.getElementById('seniorModalTitle').textContent = titleMap[actionValue] || "บันทึกการดำเนินการ";

        document.getElementById('seniorModal').style.display = 'block';
    }

    function closeSeniorModal() {
        document.getElementById('seniorModal').style.display = 'none';
    }

    function openManagerModal(repairId, actionValue) {
        const form = document.getElementById('managerForm');
        form.action = `/repair/${repairId}/manager-action`;

        let existingInput = document.getElementById('manager_action_input');
        if (existingInput) existingInput.remove();

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'action';
        input.value = actionValue;
        input.id = 'manager_action_input';
        form.appendChild(input);

        const titleMap = {
            assign: "มอบหมายงาน",
            approve_parts: "อนุมัติอุปกรณ์"
        };

        document.getElementById('managerModalTitle').textContent = titleMap[actionValue] || "จัดการคำร้อง";

        const selectWrapper = document.getElementById('technicianSelectWrapper');
        if (actionValue === 'assign') {
            selectWrapper.style.display = 'block';
        } else {
            selectWrapper.style.display = 'none';
        }

        document.getElementById('managerModal').style.display = 'block';
    }

    function closeManagerModal() {
        document.getElementById('managerModal').style.display = 'none';
    }

    function openPartsModal(repairId) {
        document.getElementById('parts_repair_id').value = repairId;
        document.getElementById('partsRequestForm').action = `/repair/${repairId}/parts-request`;
        document.getElementById('partsRequestModal').style.display = 'block';
    }

    function closePartsModal() {
        document.getElementById('partsRequestModal').style.display = 'none';
    }


</script>

</html>