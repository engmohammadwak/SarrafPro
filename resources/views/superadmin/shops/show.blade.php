@extends('layouts.superadmin')
@section('title', $shop->name . ' - صراف برو')
@section('page-title', 'تفاصيل المحل')

@section('content')
<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px;"></i> {{ $shop->name }}</h3>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('superadmin.shops.edit', $shop) }}" class="btn btn-gold btn-sm">
                <i class="fas fa-edit"></i> تعديل
            </a>
            <a href="{{ route('superadmin.shops.index') }}" class="btn btn-sm" style="background:var(--border);color:var(--text-primary);">
                <i class="fas fa-arrow-right"></i> رجوع
            </a>
        </div>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:20px;">
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">اسم المحل</p>
                <p style="font-weight:600;">{{ $shop->name }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">المدير</p>
                <p style="font-weight:600;">{{ $shop->admin->name ?? 'غير محدد' }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">المدينة</p>
                <p style="font-weight:600;">{{ $shop->city ?? '-' }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الهاتف</p>
                <p style="font-weight:600;">{{ $shop->phone ?? '-' }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الحالة</p>
                @if($shop->status === 'active')
                    <span class="badge badge-success">نشط</span>
                @elseif($shop->status === 'suspended')
                    <span class="badge badge-danger">موقوف</span>
                @else
                    <span class="badge badge-warning">معلق</span>
                @endif
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">تاريخ التسجيل</p>
                <p style="font-weight:600;">{{ $shop->created_at->format('Y-m-d') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
