@extends('layouts.superadmin')
@section('title', 'إضافة مندوب')
@section('page-title', 'إضافة مندوب جديد')
@section('content')
<div style="max-width:600px">
<div class="card">
    <div class="card-header"><h3>بيانات المندوب</h3></div>
    <div class="card-body">
        @if($errors->any())
        <div style="background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.25);color:#dc2626;padding:12px 16px;border-radius:10px;margin-bottom:20px;font-size:14px">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
        </div>
        @endif
        <form method="POST" action="{{ route('superadmin.agents.store') }}">
        @csrf
        <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">الاسم الكامل</label>
            <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">Username</label>
            <input type="text" name="username" value="{{ old('username') }}" placeholder="agent_name" style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:8px;font-family:monospace;font-size:14px;direction:ltr">
        </div>
        <div style="margin-bottom:16px">
            <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">البريد الإلكتروني</label>
            <input type="email" name="email" value="{{ old('email') }}" required style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px;direction:ltr">
        </div>
        <div style="margin-bottom:24px">
            <label style="display:block;margin-bottom:6px;font-weight:600;font-size:14px">كلمة المرور</label>
            <input type="password" name="password" required style="width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">
        </div>
        <div style="display:flex;gap:12px">
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> إضافة</button>
            <a href="{{ route('superadmin.agents.index') }}" class="btn" style="background:#f3f4f6;color:#374151">رجوع</a>
        </div>
        </form>
    </div>
</div>
</div>
@endsection
