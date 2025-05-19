@extends('layouts.app')

@section('content')
    <h1>Welcome to Repair System</h1>
@endsection

@section('content')
    <div class="container">
        <h1>รายการแจ้งซ่อม</h1>

        <a href="{{ route('repair.create') }}" class="btn btn-primary mb-3">แจ้งซ่อมใหม่</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>เลขที่</th>
                    <th>หัวข้อ</th>
                    <th>แผนก</th>
                    <th>สถานะ</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
@endsection
