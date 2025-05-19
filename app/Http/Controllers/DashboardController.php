<?php

namespace App\Http\Controllers;

use App\Models\PartsRequest;
use Illuminate\Http\Request;
use App\Models\Part;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $parts = Part::all();

        if ($user->role->name === 'technician_senior') {
            // เฉพาะ technician_senior เห็นเฉพาะรายการที่ส่งต่อมา
            $repairs = \App\Models\RepairRequest::with('logs')
                ->where('status', 'senior_required')
                ->orderBy('created_at', 'desc')
                ->get();
        } elseif (in_array($user->role->name, ['technician', 'it_manager'])) {
            // technician กับ it_manager เห็นทั้งหมด
            $repairs = \App\Models\RepairRequest::with('logs')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // ผู้ใช้ทั่วไปเห็นเฉพาะของตัวเอง
            $repairs = \App\Models\RepairRequest::with('logs')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // แจ้งเตือนผู้ใช้ถ้ามีสถานะ waiting_info
        $hasWaitingInfo = false;
        if ($user->role->name === 'user') {
            foreach ($repairs as $repair) {
                if ($repair->logs->contains('to_status', 'waiting_info')) {
                    $hasWaitingInfo = true;
                    break;
                }
            }
        }


        $technicians = \App\Models\User::whereHas('role', function ($q) {
            $q->whereIn('name', ['technician', 'technician_lvl1', 'technician_lvl2']);
        })->get();

        return view('dashboard', compact('repairs', 'hasWaitingInfo', 'technicians', 'parts'));

    }
}
