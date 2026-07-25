@extends('layouts.admin')
@section('title', 'لوحة التحكم - ' . (auth()->user()->shop->name ?? 'صراف برو'))
@section('page-title', 'لوحة التحكم')

@section('content')
<div class="stats-grid">
    <div class="stat-card gold">
        <div class="stat-icon"><i class="fas fa-exchange-alt"></i></div>
        <div class="stat-info">
            <h4>0</h4>
            <p>عمليات اليوم</p>
        </div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h4>0</h4>
            <p>العملاء</p>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon"><i class="fas fa-wallet"></i></div>
        <div class="stat-info">
            <h4>{{ number_format(auth()->user()->shop->balance ?? 0, 2) }}</h4>
            <p>الرصيد الحالي</p>
        </div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
        <div class="stat-info">
            <h4>0</h4>
            <p>عمليات هذا الشهر</p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fas fa-store" style="color:var(--accent);margin-left:8px;"></i> معلومات المحل</h3>
    </div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">اسم المحل</p>
                <p style="font-weight:700;font-size:16px;">{{ auth()->user()->shop->name ?? '-' }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">رقم الترخيص</p>
                <p style="font-weight:600;">{{ auth()->user()->shop->license_number ?? '-' }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">المدينة</p>
                <p style="font-weight:600;">{{ auth()->user()->shop->city ?? '-' }}</p>
            </div>
            <div>
                <p style="color:var(--text-muted);font-size:13px;margin-bottom:4px;">الحالة</p>
                @php $status = auth()->user()->shop->status ?? 'active'; @endphp
                <span class="badge badge-{{ $status === 'active' ? 'success' : 'danger' }}">
                    {{ $status === 'active' ? 'نشط' : 'موقوف' }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
