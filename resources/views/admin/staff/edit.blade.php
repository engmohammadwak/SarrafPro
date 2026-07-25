@extends('layouts.admin')
@section('title', 'تعديل موظف')
@section('page-title', 'تعديل بيانات الموظف')
@section('content')
<div style="max-width:600px">
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px;border-radius:12px;margin-bottom:20px">
        <ul style="margin:0;padding-right:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif
    <form action="{{ route('admin.staff.update',$staff) }}" method="POST">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-user-edit" style="color:var(--accent);margin-left:8px"></i> تعديل: {{ $staff->user->name }}</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">اسم الموظف</label>
                    <input type="text" name="name" value="{{ old('name',$staff->user->name) }}" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الإيميل</label>
                    <input type="email" name="email" value="{{ old('email',$staff->user->email) }}" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الدور</label>
                    <input type="text" name="role" value="{{ old('role',$staff->role) }}" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">كلمة مرور جديدة <span style="font-size:12px;color:var(--text-muted)">(اتركها فارغة للإبقاء)</span></label>
                    <input type="password" name="password" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ</button>
            <a href="{{ route('admin.staff.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>
@endsection
