<?php

namespace App\Http\Controllers;

use App\Models\PartsRequest;
use App\Models\RepairRequest;
use Illuminate\Http\Request;

class RepairRequestController extends Controller
{
    public function index()
    {
        $requests = RepairRequest::with('user', 'department')->get();
        return view('repair.index', compact('requests'));
    }

    public function create()
    {
        return view('repair.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'urgency_level' => 'required|in:critical,high,normal',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $repair = new \App\Models\RepairRequest();
        $repair->title = $request->title;
        $repair->description = $request->description;
        $repair->urgency_level = $request->urgency_level;
        $repair->user_id = auth()->id();
        $repair->department_id = auth()->user()->department_id;
        $repair->status = 'pending';
        $repair->created_at = now();
        $repair->save();

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');

            \Log::info('File uploaded', [
                'name' => $file->getClientOriginalName(),
                'mime' => $file->getClientMimeType(),
            ]);

            $path = $file->store('uploads', 'public');

            \App\Models\RepairAttachment::create([
                'repair_request_id' => $repair->id,
                'file_path' => $path,
                'file_type' => $file->getClientMimeType(),
                'created_at' => now(),
            ]);
        }


        return redirect()->route('dashboard')->with('success', 'แจ้งซ่อมเรียบร้อยแล้ว');
    }
    public function show($id)
    {
        $repair = \App\Models\RepairRequest::with('attachment')->findOrFail($id);
        return view('repair.show', compact('repair'));
    }


    public function edit($id)
    {
        $repair = \App\Models\RepairRequest::with('attachments')->findOrFail($id);
        return view('repair.edit', compact('repair'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'urgency_level' => 'required|in:critical,high,normal',
            'attachment' => 'nullable|file|max:2048',
        ]);

        $repair = \App\Models\RepairRequest::findOrFail($id);
        $repair->title = $request->title;
        $repair->description = $request->description;
        $repair->urgency_level = $request->urgency_level;
        $repair->updated_at = now();
        $repair->save();

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('uploads', 'public');

            \App\Models\RepairAttachment::create([
                'repair_request_id' => $repair->id,
                'file_path' => $path,
                'file_type' => $request->file('attachment')->getClientMimeType(),
                'created_at' => now(),
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'แก้ไขคำขอแจ้งซ่อมเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        RepairRequest::destroy($id);
        return redirect()->route('dashboard')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    public function addAttachment(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|max:5120', // 5MB
        ]);

        $repair = RepairRequest::findOrFail($id);

        $path = $request->file('file')->store('uploads', 'public');

        \App\Models\RepairAttachment::create([
            'repair_request_id' => $repair->id,
            'file_path' => $path,
            'file_type' => $request->file('file')->getClientMimeType(),
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'อัปโหลดไฟล์เรียบร้อยแล้ว');
    }

    public function cancel($id)
    {
        $repair = \App\Models\RepairRequest::findOrFail($id);

        if ($repair->status === 'pending') {
            $repair->status = 'cancelled';
            $repair->save();

            return redirect()->route('dashboard')->with('success', 'ยกเลิกการแจ้งซ่อมเรียบร้อยแล้ว');
        }

        return redirect()->route('dashboard')->with('error', 'ไม่สามารถยกเลิกรายการนี้ได้');
    }

    public function history()
    {
        $repairs = \App\Models\RepairRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('repair.history', compact('repairs'));
    }


    public function updateStatus(Request $request, $id)
    {
        $repair = RepairRequest::findOrFail($id);
        $oldStatus = $repair->status;
        $note = '';

        switch ($request->action) {
            case 'accept':
                $repair->status = 'in_progress';
                $note = 'ช่างรับเรื่องแล้ว';
                break;
            case 'request_info':
                $repair->status = 'waiting_info';
                $note = 'ขอข้อมูลเพิ่มเติม';
                break;
            case 'forward_to_senior':
                $repair->status = 'senior_required';
                $note = 'ส่งต่อให้ช่างอาวุโส';
                break;
            case 'complete':
                $repair->status = 'completed';
                $note = 'เสร็จสิ้น';
                break;
            default:
                return back()->withErrors(['ไม่รู้จักการกระทำนี้']);
        }

        $repair->save();

        \App\Models\RepairLog::create([
            'repair_request_id' => $repair->id,
            'changed_by_user_id' => auth()->id(),
            'from_status' => $oldStatus,
            'to_status' => $repair->status,
            'note' => $note,
            'created_at' => now(),
        ]);

        return back()->with('success', 'อัปเดตสถานะและบันทึก log สำเร็จ');
    }


    public function logAction(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        $repair = RepairRequest::findOrFail($id);

        \App\Models\RepairLog::create([
            'repair_request_id' => $repair->id,
            'changed_by_user_id' => auth()->id(),
            'from_status' => $repair->status,
            'to_status' => $request->action === 'request_info' ? 'waiting_info' : $repair->status,
            'note' => $request->note,
            'created_at' => now(),
        ]);

        if ($request->action === 'request_info') {
            $repair->status = 'waiting_info';
            $repair->save();
        }

        return back()->with('success', 'บันทึกเรียบร้อยแล้ว');
    }
    public function seniorAction(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:take_over,send_back_to_junior,request_parts,complete,log_fix',
            'note' => 'nullable|string|max:1000'
        ]);

        $repair = RepairRequest::findOrFail($id);
        $user = auth()->user();
        $note = $request->note ?? '';
        $oldStatus = $repair->status;
        $newStatus = $oldStatus;

        switch ($request->action) {
            case 'take_over':
                $newStatus = 'in_progress';
                break;
            case 'send_back_to_junior':
                $newStatus = 'returned_to_junior';
                break;
            case 'request_parts':
                $newStatus = 'waiting_parts_approval';
                break;
            case 'complete':
                $newStatus = 'completed';
                break;
            case 'log_fix':
                // newStatus เหมือนเดิม
                break;
            default:
                return back()->withErrors(['action ไม่ถูกต้อง']);
        }

        if ($newStatus !== $oldStatus) {
            $repair->status = $newStatus;
            $repair->save();
        }

        // บันทึก log 
        \App\Models\RepairLog::create([
            'repair_request_id' => $repair->id,
            'changed_by_user_id' => $user->id,
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'note' => $note,
            'created_at' => now(),
        ]);

        return back()->with('success', 'บันทึกการดำเนินการเรียบร้อยแล้ว');
    }

    public function setUrgency(Request $request, $id)
    {
        $request->validate([
            'urgency_level' => 'required|in:normal,high,critical'
        ]);

        $repair = RepairRequest::findOrFail($id);
        $repair->urgency_level = $request->urgency_level;
        $repair->save();

        return redirect()->back()->with('success', 'ปรับระดับความเร่งด่วนเรียบร้อยแล้ว');
    }


    public function storePartsRequest(Request $request, $id)
    {
        $request->validate([
            'part_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        PartsRequest::create([
            'repair_request_id' => $id,
            'requested_by_user_id' => auth()->id(),
            'part_name' => $request->part_name,
            'quantity' => $request->quantity,
            'status' => 'pending',
            'approved_by_user_id' => null,
        ]);

        return back()->with('success', 'ส่งคำขออะไหล่เรียบร้อยแล้ว');
    }


    public function managerAction(Request $request, $id)
    {
        $action = $request->action;
        $repair = RepairRequest::findOrFail($id);

        if ($action === 'assign') {
            $request->validate([
                'technician_id' => 'required|exists:users,id',
            ]);

            DB::table('repair_actions')->insert([
                'repair_request_id' => $id,
                'technician_id' => $request->technician_id,
                'level' => 1,
                'action_details' => $request->note,
                'created_at' => now(),
            ]);

            $repair->status = 'in_progress';
            $repair->save();
        }

        return back()->with('success', 'ดำเนินการสำเร็จ');
    }



}
