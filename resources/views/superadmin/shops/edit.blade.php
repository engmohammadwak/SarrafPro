@extends('layouts.superadmin')
@section('title', 'تعديل ' . $shop->name . ' - صراف برو')
@section('page-title', 'تعديل المحل')

@section('content')
<div class="card" style="max-width:600px;">
    <div class="card-header">
        <h3><i class="fas fa-edit" style="color:var(--accent);margin-left:8px;"></i> تعديل: {{ $shop->name }}</h3>
        <a href="{{ route('superadmin.shops.index') }}" class="btn btn-sm" style="background:var(--border);color:var(--text-primary);">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
    <div class="card-body">
        @if($errors->any())
        <div class="alert" style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#ef4444;padding:12px 16px;border-radius:8px;margin-bottom:20px;">
            <ul style="margin:0;padding-right:16px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('superadmin.shops.update', $shop) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="margin-bottom:16px;">
                <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-secondary);">اسم المحل</label>
                <input type="text" name="name" value="{{ old('name', $shop->name) }}"
                    style="width:100%;padding:10px 14px;background:var(--input-bg,var(--card-bg));border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:14px;"
                    required>
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block;margin-bottom:6px;font-size:14px;color:var(--text-secondary);">الحالة</label>
                <select name="status"
                    style="width:100%;padding:10px 14px;background:var(--input-bg,var(--card-bg));border:1px solid var(--border);border-radius:8px;color:var(--text-primary);font-size:14px;">
                    <option value="active" {{ old('status', $shop->status) === 'active' ? 'selected' : '' }}>نشط</option>
                    <option value="suspended" {{ old('status', $shop->status) === 'suspended' ? 'selected' : '' }}>موقوف</option>
                    <option value="pending" {{ old('status', $shop->status) === 'pending' ? 'selected' : '' }}>معلق</option>
                </select>
            </div>

            <button type="submit" class="btn btn-gold">
                <i class="fas fa-save" style="margin-left:6px;"></i> حفظ التعديلات
            </button>
        </form>
    </div>
</div>
@endsection
