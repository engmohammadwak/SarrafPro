@extends('layouts.admin')
@section('title', 'إضافة عميل')
@section('page-title', 'إضافة عميل جديد')
@section('content')
<div style="max-width:600px">
    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:14px;border-radius:12px;margin-bottom:20px">
        <ul style="margin:0;padding-right:18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif
    <form action="{{ route('admin.customers.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-user-plus" style="color:var(--accent);margin-left:8px"></i> بيانات العميل</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الاسم *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">رقم الهوية</label>
                    <input type="text" name="id_number" value="{{ old('id_number') }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الجنسية</label>
                    <input type="text" name="nationality" value="{{ old('nationality') }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>

                    <div style="grid-column:1/-1"><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">ملاحظات</label>
                    <textarea name="notes" rows="3" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px">{{ old('notes') }}</textarea></div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px;display:flex;gap:12px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-plus"></i> إضافة</button>
            <a href="{{ route('admin.customers.index') }}" class="btn" style="background:var(--border);color:var(--text-dark)"><i class="fas fa-times"></i> إلغاء</a>
        </div>
    </form>
</div>
@endsection
