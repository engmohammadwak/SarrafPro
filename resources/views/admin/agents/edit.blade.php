@extends('layouts.admin')
@section('title', 'تعديل مندوب')
@section('page-title', 'تعديل بيانات المندوب')
@section('content')
<div style="max-width:600px">
    <form action="{{ route('admin.agents.update',$agent) }}" method="POST">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-edit" style="color:var(--accent);margin-left:8px"></i> {{ $agent->name }}</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الاسم</label>
                    <input type="text" name="name" value="{{ old('name',$agent->name) }}" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone',$agent->phone) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الدولة</label>
                    <input type="text" name="country" value="{{ old('country',$agent->country) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الشركة</label>
                    <input type="text" name="company" value="{{ old('company',$agent->company) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ</button>
            <a href="{{ route('admin.agents.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>
@endsection
