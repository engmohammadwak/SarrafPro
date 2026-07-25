@extends('layouts.admin')
@section('title', 'إعدادات المحل')
@section('page-title', 'إعدادات المحل')
@section('content')
<div style="max-width:600px">
    @if(session('success'))
    <div style="background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);color:#065f46;padding:14px;border-radius:12px;margin-bottom:20px">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf @method('PUT')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-cog" style="color:var(--accent);margin-left:8px"></i> بيانات المحل</h3></div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">اسم المحل (عربي)</label>
                    <input type="text" name="name" value="{{ old('name',$shop->name) }}" required style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">اسم المحل (إنجليزي)</label>
                    <input type="text" name="name_en" value="{{ old('name_en',$shop->name_en) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">الهاتف</label>
                    <input type="text" name="phone" value="{{ old('phone',$shop->phone) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                    <div><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">المدينة</label>
                    <input type="text" name="city" value="{{ old('city',$shop->city) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                    <div style="grid-column:1/-1"><label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-muted)">رقم الترخيص</label>
                    <input type="text" name="license_number" value="{{ old('license_number',$shop->license_number) }}" style="width:100%;padding:10px 14px;background:#f8f9fc;border:1px solid var(--border);border-radius:8px;font-family:Tajawal,sans-serif;font-size:14px"></div>
                </div>
            </div>
        </div>
        <div style="margin-top:16px">
            <button type="submit" class="btn btn-gold"><i class="fas fa-save"></i> حفظ الإعدادات</button>
        </div>
    </form>
</div>
@endsection
