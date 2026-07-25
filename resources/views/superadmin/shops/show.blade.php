@extends('layouts.superadmin')
@section('title', $shop->name . ' - صراف برو')
@section('page-title', 'تفاصيل المحل')

@section('content')
<div style="display:flex;flex-direction:column;gap:20px;max-width:800px;">

    {{-- Shop Info --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px;"></i> {{ $shop->name }}</h3>
            <div style="display:flex;gap:8px;">
                <a href="{{ route('superadmin.shops.edit', $shop) }}" class="btn btn-gold btn-sm">
                    <i class="fas fa-edit"></i> تعديل
                </a>
                <a href="{{ route('superadmin.shops.index') }}" class="btn btn-sm" style="background:var(--border);color:var(--text-dark);">
                    <i class="fas fa-arrow-right"></i> رجوع
                </a>
            </div>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">اسم المحل (عربي)</p>
                    <p style="font-weight:600;">{{ $shop->name }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">اسم المحل (إنجليزي)</p>
                    <p style="font-weight:600;">{{ $shop->name_en ?? '-' }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">رقم الترخيص</p>
                    <p style="font-weight:600;">{{ $shop->license_number ?? '-' }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الهاتف</p>
                    <p style="font-weight:600;">{{ $shop->phone ?? '-' }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">المدينة</p>
                    <p style="font-weight:600;">{{ $shop->city ?? '-' }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الحالة</p>
                    @if($shop->status === 'active')
                        <span class="badge badge-success"><i class="fas fa-circle" style="font-size:8px;"></i> نشط</span>
                    @elseif($shop->status === 'suspended')
                        <span class="badge badge-danger"><i class="fas fa-circle" style="font-size:8px;"></i> موقوف</span>
                    @else
                        <span class="badge badge-warning"><i class="fas fa-circle" style="font-size:8px;"></i> معلق</span>
                    @endif
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الرصيد</p>
                    <p style="font-weight:700;color:var(--accent);font-size:18px;">{{ number_format($shop->balance ?? 0, 4) }} <span style="font-size:13px;color:var(--text-muted);">OMR</span></p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">تاريخ التسجيل</p>
                    <p style="font-weight:600;">{{ $shop->created_at->format('Y-m-d') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Admin Account Info --}}
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-user-tie" style="color:var(--accent);margin-left:8px;"></i> حساب مدير المحل</h3>
        </div>
        <div class="card-body">
            @if($shop->admin)
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">اسم المدير</p>
                    <p style="font-weight:600;">{{ $shop->admin->name }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">البريد الإلكتروني</p>
                    <p style="font-weight:600;">{{ $shop->admin->email }}</p>
                </div>
                <div>
                    <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">رابط الدخول</p>
                    <a href="{{ url('/admin/login') }}" target="_blank" style="color:var(--accent);font-weight:600;font-size:13px;">
                        <i class="fas fa-external-link-alt" style="margin-left:4px;"></i> /admin/login
                    </a>
                </div>
            </div>
            @else
            <p style="color:var(--text-muted);"><i class="fas fa-exclamation-triangle" style="margin-left:6px;color:var(--warning);"></i> لا يوجد مدير مرتبط بهذا المحل</p>
            @endif
        </div>
    </div>

</div>
@endsection
